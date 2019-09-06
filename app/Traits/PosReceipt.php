<?php
namespace App\Traits;

use App\Order;
use App\OrderDetail;
use App\PromotionDiscount;
use App\Libraries\StarCloudPrintStarLineModeJob;

trait PosReceipt {
	private $MAX_CHARS = 32;

	function createPOSReceipt($orderId)
    {
        // Get order/store/company detail
        $order = Order::from('orders as O')
            ->select(['O.order_id', 'O.customer_order_id', 'C.currencies', 'O.order_total', 'O.final_order_total', 'O.delivery_type', 'O.delivery_charge', 'S.store_name', 'S.phone'])
            ->join('store AS S', 'S.store_id', '=', 'O.store_id')
            ->join('company AS C','C.company_id', '=', 'O.company_id')
            ->where(['O.order_id' => $orderId])
            ->first();
        
        if($order)
        {
            // Get order item details belongs to order
            $orderDetail = OrderDetail::from('order_details AS OD')
                ->select(['OD.product_quality', 'OD.price', 'P.product_name'])
                ->join('product AS P', 'P.product_id', '=', 'OD.product_id')
                ->where('OD.order_id', $orderId)
                ->get();
            
            // Get order discount if applied
            $orderDiscount = PromotionDiscount::from('promotion_discount AS PD')
                ->select(['PD.discount_value'])
                ->join('order_customer_discount AS OCD', 'OCD.discount_id', '=', 'PD.id')
                ->where(['OCD.order_id' => $orderId])
                ->first();

            $printerMac = '00.11.62.1b.e3.53';

            // 
            $fileName = "{$printerMac}-{$order->order_id}.txt";
            $printer = new StarCloudPrintStarLineModeJob($printerMac, $fileName);

            // Header
            $printer->set_codepage("\x20\n");
            $printer->set_text_center_align();
            $printer->add_text_line($order->store_name);
            $printer->add_text_line("TEL: {$order->phone}\n");
            $printer->set_text_emphasized();
            $printer->add_text_line("{$order->customer_order_id}");
            $printer->cancel_text_emphasized();
            $printer->add_text_line($this->get_seperator_dashed());

            // Cart Item
            $printer->set_text_right_align();
            if($orderDetail)
            {
                foreach($orderDetail as $row)
                {
                    $arrIndex1 = "{$row->product_quality} {$row->product_name}";
                    $arrIndex2 = number_format(($row->product_quality*$row->price), 2, '.', '')." ".$order->currencies;
                    $printer->add_text_line($this->get_column_separated_data(array($arrIndex1, $arrIndex2)));
                }
                $printer->add_text_line($this->get_seperator_dashed());
            }

            // Total
            $subTotal = $order->order_total;
            // $printer->add_text_line($this->get_padded_text(__('messages.subTotal'), number_format($subTotal, 2, '.', '').' '.$order->currencies));
            // $printer->add_text_line($this->get_column_separated_data(array(__('messages.subTotal'), number_format($subTotal, 2, '.', '').' '.$order->currencies)));
            if($orderDiscount)
            {
                $discountAmount = ($order->final_order_total*$orderDiscount->discount_value/100);
                $printer->add_text_line($this->get_column_separated_data(array(__('messages.discount'), number_format($discountAmount, 2, '.', '')." ".$order->currencies)));
            }
            if($order->delivery_type == 3 && $order->delivery_charge)
            {
                $delivery_charge = $order->delivery_charge;
                $printer->add_text_line($this->get_column_separated_data(array(__('messages.delivery_charge'), number_format($delivery_charge, 2, '.', '')." ".$order->currencies)));
            }
            $vat = number_format(($order->final_order_total*12/100), 2, '.', '')." ".$order->currencies;
            $printer->add_text_line($this->get_column_separated_data(array(__('messages.vat'), $vat)));
            $total = number_format(($order->final_order_total), 2, '.', '')." ".$order->currencies;
            $printer->set_text_emphasized();
            $printer->add_text_line($this->get_column_separated_data(array(__('messages.TOTAL'), $total)));
            $printer->cancel_text_emphasized();
            $printer->add_text_line($this->get_seperator_dashed());

            // Footer
            $printer->set_text_center_align();
            $printer->add_text_line(__('messages.printFooterText'));
            $printer->add_text_line($this->get_seperator_dashed());
            $printer->add_text_line("\n".date("d M Y").", ".date("H:i")."\n");

            $printer->saveJob();
        }
    }

    function get_column_separated_data($columns)
    {
        $total_columns = count($columns);
        
        if ($total_columns == 0) return "";
        if ($total_columns == 1) return $columns[0];
        if ($total_columns == 2)
        {
            $total_characters = strlen($columns[0])+strlen($columns[1]);
            $total_whitespace = $this->MAX_CHARS - $total_characters;
            // echo $this->MAX_CHARS.'-'.$total_characters.'='.$total_whitespace; exit;
            if ($total_whitespace < 0)
            {
                $total_whitespace = ($this->MAX_CHARS*2) - $total_characters;
                return $columns[0].str_repeat(" ", $total_whitespace).$columns[1];
                // return ""
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
}
?>