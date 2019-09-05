<?php
namespace App\Traits;

use App\Libraries\StarCloudPrintStarLineModeJob;

trait PosReceipt {
	private $MAX_CHARS = 32;

	function createPOSReceipt($storeDetail, $order, $orderDetails)
    {
        $printerMac = '00.11.62.1b.e3.53';

        // 
        $fileName = "{$printerMac}-{$order->order_id}.txt";
        $printer = new StarCloudPrintStarLineModeJob($printerMac, $fileName);

        // Header
        $printer->set_codepage("\x20\n");
        $printer->set_text_center_align();
        $printer->add_text_line($storeDetail->store_name);
        $printer->add_text_line("TEL: {$storeDetail->phone}\n");
        $printer->add_text_line("Order no.: #{$order->customer_order_id}");
        $printer->add_text_line($this->get_seperator());

        // Cart Item
        if($orderDetails)
        {
            $printer->set_text_right_align();
            $printer->set_text_emphasized();
            foreach($orderDetails as $row)
            {
                $arrIndex1 = "{$row->product_quality} {$row->product_name}";
                $arrIndex2 = number_format(($row->product_quality*$row->price), 2, '.', '')." ".$order->currencies;
                $printer->add_text_line($this->get_column_separated_data(array($arrIndex1, $arrIndex2)));
            }
            $printer->add_text_line($this->get_column_separated_data(array("2 Savenska kottbullar Kramig", "100.00 kr")));

            $printer->cancel_text_emphasized();
            $printer->add_text_line($this->get_seperator());
        }

        // Total
        $total = number_format($order->order_total, 2, '.', '')." ".$order->currencies;
        $printer->set_text_right_align();
        $printer->set_text_emphasized();
        $printer->add_text_line($this->get_padded_text("TOTAL", $total));
        $printer->cancel_text_emphasized();
        $printer->add_text_line($this->get_seperator());

        // Footer
        $printer->set_text_center_align();
        $printer->add_text_line("Thank you for shopping at {$storeDetail->store_name}");
        $printer->add_text_line($this->get_seperator());
        // $printer->add_text_line("");
        $printer->add_text_line("\n".date("d M Y")."\n".date("H:i")."\n");

        $printer->saveJob();
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