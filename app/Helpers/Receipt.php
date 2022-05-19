<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\Pdf\PDFOptions;
use App\Http\Controllers\Settings;
use App\Models\ItemBound;

class Receipt
{
    public $html;

    static function print_order($order_id)
    {
        $order = ItemBound::find($order_id);
        $items = Order::get_items($order_id);
        $customer = Order::get_customer($order_id);
        $options = new PDFOptions;
        $pdf_options = $options->getAll();
        $paper_size = $options->pdf_sizes()['order'];
        $data = [
            'logo' => 'http://joyice.herokuapp.com/images/logo.png',//public_path('images/').Settings::get('app_logo'),
            'company' => Settings::get('app_name'),
            'contact_no' => Settings::get('app_contact_no'),
            'piece_unit' => Settings::get('piece_unit'),
            'order_number' => $order->order_number,
            'styles' => $options->styles(),
            'items' => $items,
            'customer' => $customer
        ];

        $strtime = strtotime(date('Y-m-d h:i'));
        $pdf = PDF::loadView('pdf.order-receipt',[
            'data' => $data
        ]);
        $pdf->setOptions($pdf_options);
        $pdf->setPaper($paper_size['size'], $paper_size['orient']);
        return $pdf->download("order-receipt-{$order_id}-{$strtime}.pdf");
    }
}