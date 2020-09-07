<?php
namespace App\Traits;

use App\Order;
use App\OrderDetail;
use App\PromotionDiscount;
use App\OrderCustomerLoyalty;
use DB;
use App\Libraries\StarCloudPrintStarLineModeJob;

trait PosReceipt {
    private $MAX_CHARS = 32; // 32 char in '2 inch' printer

    function createPOSReceipt($orderId)
    {
        // Get order/store/company detail
        $order = Order::from('orders as O')
            ->select(['O.order_id', 'O.customer_order_id', 'O.delivery_type', 'O.delivery_at_door', 'O.check_deliveryDate', 'O.deliver_time', 'O.order_total', 'O.final_order_total', 'O.delivery_charge', 'O.online_paid', 'C.currencies', 'S.store_name', 'S.phone', 'SP.printer_type', 'SP.mac_address', 'SP.print_copy', 'CA.full_name', 'CA.mobile', 'CA.entry_code', 'CA.apt_no', 'CA.company_name', 'CA.other_info', 'CA.address', 'CA.street', 'CA.city'])
            ->join('store AS S', 'S.store_id', '=', 'O.store_id')
            ->join('company AS C','C.company_id', '=', 'O.company_id')
            ->leftJoin('store_printers AS SP', 'SP.store_id', '=', 'S.store_id')
            ->leftJoin('customer_addresses AS CA', 'CA.id', '=', 'O.user_address_id')
            ->where(['O.order_id' => $orderId])
            ->first();
        
        // Check if printer settings exist
        if($order && (isset($order->mac_address) && $order->mac_address != null && $order->print_copy != null))
        {
            // Update 'MAX_CHARS', if its '3 inch' printer
            if($order->printer_type == '2')
            {
                $this->MAX_CHARS = 48; // 48 char in '3 inch' printer
            }

            // Get order item details belongs to order
            $orderDetail = OrderDetail::from('order_details AS OD')
                ->select(['OD.product_quality', 'OD.product_description', 'OD.price', 'P.product_name'])
                ->join('product AS P', 'P.product_id', '=', 'OD.product_id')
                ->where('OD.order_id', $orderId)
                ->get();

            // Check if loyalty exist for order
            $orderCustomerLoyalty = OrderCustomerLoyalty::select()
                ->where(['order_id' => $orderId])
                ->first();

            if($orderCustomerLoyalty)
            {
                $quantity_offered = OrderDetail::select([DB::raw('SUM(quantity_free) AS quantity_offered')])
                    ->where(['order_id' => $orderId])
                    ->first()->quantity_offered;
                $loyaltyOfferApplied = __('messages.loyaltyOfferApplied', ['loyalty_quantity_free' => $quantity_offered]);
                $loyaltyOfferApplied = $this->replaceAsciiToHex($loyaltyOfferApplied);
            }
            
            // Get order discount if applied
            $orderDiscount = PromotionDiscount::from('promotion_discount AS PD')
                ->select(['PD.discount_value'])
                ->join('order_customer_discount AS OCD', 'OCD.discount_id', '=', 'PD.id')
                ->where(['OCD.order_id' => $orderId])
                ->first();

            $printerMac = $this->getPrinterFolder($order->mac_address);
            $printerMac = strtolower($printerMac);

            // 
            $fileName = array();
            for($i = $order->print_copy; $i >= 1; $i--)
            {
                $fileName[] = "{$printerMac}-{$orderId}-{$i}.txt";
            }

            if( !empty($fileName) )
            {
                $printer = new StarCloudPrintStarLineModeJob($printerMac, $fileName);

                // Header
                $printer->set_codepage("\x20\n");
                $printer->set_text_center_align();
                $printer->add_text_line(__('messages.Order Number'));
                $printer->set_text_emphasized();
                $printer->add_text_line("{$order->customer_order_id}\n");
                $printer->add_text_line($this->replaceAsciiToHex($order->store_name));
                $printer->add_text_line("TEL: {$order->phone}\n");
                $printer->cancel_text_emphasized();
                
                $printer->set_text_right_align();
                if($order->delivery_type == 3)
                {
                    $printer->set_text_emphasized();
                    $printer->add_text_line(__('messages.deliverTo'));
                    $printer->cancel_text_emphasized();
                    $address = $this->replaceAsciiToHex($order->full_name)."\n";
                    if( !is_null($order->address) && !empty($order->address) )
                    {
                        $address .= $this->replaceAsciiToHex($order->address)."\n";
                    }
                    else
                    {
                        $txt = '';

                        if( !is_null($order->entry_code) )
                        {
                            $txt .= __('messages.entryCode').": {$order->entry_code}\n";
                        }

                        if( !is_null($order->apt_no) )
                        {
                            $txt .= __('messages.aptNo').": {$order->apt_no}\n";
                        }

                        if( !is_null($order->company_name) )
                        {
                            $txt .= __('messages.companyName').": {$order->company_name}\n";
                        }

                        if( !is_null($order->other_info) )
                        {
                            $txt .= __('messages.otherInfo').": {$order->other_info}\n";
                        }

                        $address .= $this->replaceAsciiToHex($txt);
                    }
                    $address .= $this->replaceAsciiToHex($order->street)."\n".$this->replaceAsciiToHex($order->city)."\n".__('messages.phone').": {$order->mobile}";
                    $printer->add_text_line($address);
                }
                $printer->add_text_line($this->get_seperator_dashed());

                // Cart Item
                if($orderDetail)
                {
                    foreach($orderDetail as $row)
                    {
                        $product_name = $this->replaceAsciiToHex($row->product_name);
                        $arrIndex1 = "{$row->product_quality} {$product_name}";
                        $arrIndex2 = number_format(($row->product_quality*$row->price), 2, '.', '')." ".$order->currencies;
                        $printer->add_text_line($this->get_column_separated_data(array($arrIndex1, $arrIndex2)));

                        // Add product comment if have
                        if( !is_null($row->product_description) )
                        {
                            $printer->add_text_line('      ('.$this->replaceAsciiToHex($row->product_description).')');
                        }
                    }
                    $printer->add_text_line($this->get_seperator_dashed());
                }

                // Discount
                if($orderDiscount)
                {
                    $discountAmount = ($order->final_order_total*$orderDiscount->discount_value/100);
                    $printer->add_text_line($this->get_column_separated_data(array(__('messages.Discount'), number_format($discountAmount, 2, '.', '')." ".$order->currencies)));
                }
                // Delivery Charge
                if($order->delivery_type == 3 && $order->delivery_charge)
                {
                    $delivery_charge = $order->delivery_charge;
                    $printer->add_text_line($this->get_column_separated_data(array(__('messages.delivery_charge'), number_format($delivery_charge, 2, '.', '')." ".$order->currencies)));
                }
                // Total
                $total = number_format(($order->final_order_total), 2, '.', '')." ".$order->currencies;
                $printer->set_text_emphasized();
                $printer->add_text_line($this->get_column_separated_data(array(__('messages.TOTAL'), $total)));
                $printer->cancel_text_emphasized();
                // Vat
                $vat = number_format(($order->final_order_total*12/100), 2, '.', '')." ".$order->currencies;
                $printer->add_text_line($this->get_column_separated_data(array(__('messages.vat'), $vat)));
                $printer->set_text_center_align();
                // Loyalty
                if($orderCustomerLoyalty)
                {
                    $printer->add_text_line($loyaltyOfferApplied);
                }
                $printer->add_text_line($this->get_seperator_dashed());

                if($order->online_paid == 1)
                {
                    $printer->set_text_emphasized();
                    $printer->add_text_line(strtoupper(__('messages.Paid')));
                    $printer->cancel_text_emphasized();
                    $printer->add_text_line($this->get_seperator_dashed());
                }

                // Check if 'deliver_at_door'
                if($order->delivery_at_door == "1" && $order->delivery_type == "3"){
                    $printer->set_text_emphasized();
                    $printer->add_text_line(__('messages.deliverAtDoor')."\n");
                    $printer->cancel_text_emphasized();
                }

                // Footer
                $orderDateTime = $order->check_deliveryDate.' '.$order->deliver_time;
                $printer->add_text_line(date("d M Y, H:i", strtotime($orderDateTime))."\n");
                
                $printer->add_text_line(__('messages.printFooterText'));

                $printer->saveJob();
            }
        }
    }

    function get_column_separated_data($columns)
    {
        $total_columns = count($columns);
        
        if ($total_columns == 0) return "";
        if ($total_columns == 1) return $columns[0];
        if ($total_columns == 2)
        {
            $columns[0] = '  '.$columns[0]; // Add whitespace before left text to get rid text overide
            $total_characters = strlen($columns[0])+strlen($columns[1]);
            $total_whitespace = $this->MAX_CHARS - $total_characters;
            // echo $this->MAX_CHARS.'-'.$total_characters.'='.$total_whitespace; exit;
            if ($total_whitespace < 0)
            {
                $total_whitespace = ($this->MAX_CHARS*2) - $total_characters;
                return $columns[0].str_repeat(" ", $total_whitespace).$columns[1];
            }
            return $columns[0].str_repeat(" ", $total_whitespace).$columns[1];
        }
        
        $total_characters = 0;
        foreach ($columns as $column)
        {
            $total_characters += strlen($column);
        }
        $total_whitespace = $this->MAX_CHARS - $total_characters;
        if ($total_whitespace < 0) return "";
        $total_spaces = $total_columns-1;
        $space_width = floor($total_whitespace / $total_spaces);
        $result = $columns[0].str_repeat(" ", $space_width);
        for ($i = 1; $i < ($total_columns-1); $i++)
        {
            $result .= $columns[$i].str_repeat(" ", $space_width);
        }
        $result .= $columns[$total_columns-1];
        
        return $result;
    }

    // Search and replace ASCII to HEX
    function replaceAsciiToHex($str)
    {
        $str = strip_tags($str);
        $search = array("Ä", "Å", "å", "ä", "Ö", "ö");
        $replace = array("\xC4", "\xC5", "\xE5", "\xE4", "\xD6", "\xF6");
        return str_replace($search, $replace, $str);
    }

    function get_seperator()
    {
        return str_repeat('_', $this->MAX_CHARS);
    }

    function get_seperator_dashed()
    {
        return str_repeat('-', $this->MAX_CHARS);
    }

    function get_padded_text($left_text, $right_text)
    {
        $current_size = strlen($left_text) + strlen($right_text);
        $spaces = " ";
        for ($i = $current_size; $i < 19; $i++)
        {
            $spaces .= " ";
        }
        return $left_text . $spaces . $right_text;
    }

    function getPrinterFolder($printerMac)
    {
        return str_replace(":", ".", $printerMac);
    }
    
    function getPrinterMac($printerFolder)
    {
        return str_replace(".", ":", $printerFolder);
    }
}
?>