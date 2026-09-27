<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use Session;
use PDF;
use Config;
use ZipArchive;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //download invoice
    public function invoice_download($id)
    {
        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if (Language::where('code', $language_code)->first()->rtl == 1) {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } else {
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        if (
            $currency_code == 'BDT' ||
            $language_code == 'bd'
        ) {
            // bengali font
            $font_family = "'Hind Siliguri','freeserif'";
        } elseif (
            $currency_code == 'KHR' ||
            $language_code == 'kh'
        ) {
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
            // }elseif($currency_code == 'ILS'){
            //     // Israeli font
            //     $font_family = "'Varela Round','sans-serif'";
        } elseif (
            $currency_code == 'AED' ||
            $currency_code == 'EGP' ||
            $language_code == 'sa' ||
            $currency_code == 'IQD' ||
            $language_code == 'ir' ||
            $language_code == 'om' ||
            $currency_code == 'ROM' ||
            $currency_code == 'SDG' ||
            $currency_code == 'ILS' ||
            $language_code == 'jo'
        ) {
            // middle east/arabic/Israeli font
            $font_family = "xbriyaz";
        } elseif ($currency_code == 'THB') {
            // thai font
            $font_family = "'Kanit','sans-serif'";
        } elseif (
            $currency_code == 'CNY' ||
            $language_code == 'zh'
        ) {
            // Chinese font
            $font_family = "'sun-exta','gb'";
        } elseif (
            $currency_code == 'MMK' ||
            $language_code == 'mm'
        ) {
            // Myanmar font
            $font_family = 'tharlon';
        } elseif (
            $currency_code == 'THB' ||
            $language_code == 'th'
        ) {
            // Thai font
            $font_family = "'zawgyi-one','sans-serif'";
        } elseif (
            $currency_code == 'USD'
        ) {
            // Thai font
            $font_family = "'Roboto','sans-serif'";
        } else {
            // general for all
            $font_family = "freeserif";
        }

        // $config = ['instanceConfigurator' => function($mpdf) {
        //     $mpdf->showImageErrors = true;
        // }];
        // mpdf config will be used in 4th params of loadview

        $config = [];

        $order = Order::findOrFail($id);
        if (in_array(auth()->user()->user_type, ['admin','staff']) || in_array(auth()->id(), [$order->user_id, $order->seller_id])) {
            return PDF::loadView('backend.invoices.invoice', [
                'order' => $order,
                'font_family' => $font_family,
                'direction' => $direction,
                'text_align' => $text_align,
                'not_text_align' => $not_text_align
            ], [], $config)->download('order-' . $order->code . '.pdf');
        }
        flash(translate("You do not have the right permission to access this invoice."))->error();
        return redirect()->route('home');
    }

    public function invoice_print($id)
    {
        $order = Order::findOrFail($id);

        // You may want to apply the same font logic here too if needed
        $language_code = Session::get('locale', Config::get('app.locale'));
        $direction = Language::where('code', $language_code)->first()->rtl == 1 ? 'rtl' : 'ltr';
        $text_align = $direction == 'rtl' ? 'right' : 'left';
        $not_text_align = $direction == 'rtl' ? 'left' : 'right';

        return view('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => "'Roboto', sans-serif", // or reuse your logic
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ]);
    }

    public function bulk_invoice_download(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (empty($orderIds)) {
            flash(translate("No orders selected."))->warning();
            return redirect()->back();
        }

        $orders = Order::whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            flash(translate("No valid orders found."))->warning();
            return redirect()->back();
        }

        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }

        $language_code = Session::get('locale', Config::get('app.locale'));
        $lang          = Language::where('code', $language_code)->first();

        if ($lang && $lang->rtl == 1) {
            $direction      = 'rtl';
            $text_align     = 'right';
            $not_text_align = 'left';
        } else {
            $direction      = 'ltr';
            $text_align     = 'left';
            $not_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            $font_family = "'Hind Siliguri','freeserif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            $font_family = "'arnamu','sans-serif'";
        } elseif (
            in_array($currency_code, ['AED', 'EGP', 'IQD', 'ROM', 'SDG', 'ILS']) ||
            in_array($language_code, ['sa', 'ir', 'om', 'jo'])
        ) {
            $font_family = "xbriyaz";
        } elseif ($currency_code == 'THB') {
            $font_family = "'Kanit','sans-serif'";
        } elseif ($currency_code == 'CNY' || $language_code == 'zh') {
            $font_family = "'sun-exta','gb'";
        } elseif ($currency_code == 'MMK' || $language_code == 'mm') {
            $font_family = 'tharlon';
        } elseif ($language_code == 'th') {
            $font_family = "'zawgyi-one','sans-serif'";
        } elseif ($currency_code == 'USD') {
            $font_family = "'Roboto','sans-serif'";
        } else {
            $font_family = "freeserif";
        }

        $tempDir = storage_path('app/temp_invoices_' . auth()->id() . '_' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . '/invoices.zip';
        $zip     = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            flash(translate("Could not create ZIP file. Please try again."))->error();
            return redirect()->back();
        }

        foreach ($orders as $order) {
            try {
                $pdfContent = PDF::loadView('backend.invoices.invoice', [
                    'order'          => $order,
                    'font_family'    => $font_family,
                    'direction'      => $direction,
                    'text_align'     => $text_align,
                    'not_text_align' => $not_text_align,
                ], [], [])->output();

                $zip->addFromString('invoice-' . $order->code . '.pdf', $pdfContent);
            } catch (\Exception $e) {
                \Log::error("Invoice PDF generation failed for order #{$order->id}: " . $e->getMessage());
            }
        }

        $zip->close();

        $fileName = 'invoices_' . now()->format('Ymd_His') . '.zip';

        register_shutdown_function(function () use ($tempDir) {
            if (is_dir($tempDir)) {
                @rmdir($tempDir);
            }
        });

        return response()->download($zipPath, $fileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    public function thermal_invoice_download($id)
    {
        $order = Order::findOrFail($id);

        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            $font_family = "'Hind Siliguri','freeserif'";
        } else {
            $font_family = "freeserif";
        }

        $pdf_style_data = [
            'font_family' => 'monospace',
            'direction'   => 'ltr',
            'text_align'  => 'left',
        ];

        $print_width = 100;

        $html = view('backend.invoices.thermal_invoice', compact(
            'order',
            'font_family',
            'pdf_style_data'
        ));

        $mpdf = new \Mpdf\Mpdf([
            'default_font' => 'monospace',
            'mode'         => 'utf-8',
            'format'       => [$print_width, 1000],
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->page  = 0;
        $mpdf->state = 0;
        unset($mpdf->pages[0]);
        $p = 'P';
        $mpdf->_setPageSize([$print_width, $mpdf->y], $p);
        $mpdf->addPage();
        $mpdf->WriteHTML($html);
        $mpdf->Output('thermal-invoice-' . $order->code . '.pdf', 'I');
    }

    public function bulk_invoice_print(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (empty($orderIds)) {
            flash(translate("No orders selected."))->warning();
            return redirect()->back();
        }

        $orders = Order::whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            flash(translate("No valid orders found."))->warning();
            return redirect()->back();
        }

        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }

        $language_code = Session::get('locale', Config::get('app.locale'));
        $lang = Language::where('code', $language_code)->first();

        if ($lang && $lang->rtl == 1) {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } else {
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            $font_family = "'Hind Siliguri','freeserif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            $font_family = "'arnamu','sans-serif'";
        } elseif (
            in_array($currency_code, ['AED', 'EGP', 'IQD', 'ROM', 'SDG', 'ILS']) ||
            in_array($language_code, ['sa', 'ir', 'om', 'jo'])
        ) {
            $font_family = "xbriyaz";
        } elseif ($currency_code == 'THB') {
            $font_family = "'Kanit','sans-serif'";
        } elseif ($currency_code == 'CNY' || $language_code == 'zh') {
            $font_family = "'sun-exta','gb'";
        } elseif ($currency_code == 'MMK' || $language_code == 'mm') {
            $font_family = 'tharlon';
        } elseif ($language_code == 'th') {
            $font_family = "'zawgyi-one','sans-serif'";
        } elseif ($currency_code == 'USD') {
            $font_family = "'Roboto','sans-serif'";
        } else {
            $font_family = "freeserif";
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        foreach ($orders as $index => $order) {
            if ($index > 0) {
                $mpdf->AddPage();
            }

            $html = view('backend.invoices.invoice', [
                'order' => $order,
                'font_family' => $font_family,
                'direction' => $direction,
                'text_align' => $text_align,
                'not_text_align' => $not_text_align
            ])->render();

            $mpdf->WriteHTML($html);
        }

        return $mpdf->Output('bulk_invoices.pdf', 'I');
    }

    public function invoice_printer($id)
    {
        $order = Order::findOrFail($id);

        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }

        $language_code = Session::get('locale', Config::get('app.locale'));
        $lang = Language::where('code', $language_code)->first();

        if ($lang && $lang->rtl == 1) {
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
        } else {
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
        }

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            $font_family = "'Hind Siliguri','freeserif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            $font_family = "'arnamu','sans-serif'";
        } elseif (
            in_array($currency_code, ['AED', 'EGP', 'IQD', 'ROM', 'SDG', 'ILS']) ||
            in_array($language_code, ['sa', 'ir', 'om', 'jo'])
        ) {
            $font_family = "xbriyaz";
        } elseif ($currency_code == 'THB') {
            $font_family = "'Kanit','sans-serif'";
        } elseif ($currency_code == 'CNY' || $language_code == 'zh') {
            $font_family = "'sun-exta','gb'";
        } elseif ($currency_code == 'MMK' || $language_code == 'mm') {
            $font_family = 'tharlon';
        } elseif ($language_code == 'th') {
            $font_family = "'zawgyi-one','sans-serif'";
        } elseif ($currency_code == 'USD') {
            $font_family = "'Roboto','sans-serif'";
        } else {
            $font_family = "freeserif";
        }

        return PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->stream('invoice-' . $order->code . '.pdf');
    }

    public function bulk_thermal_invoice_print(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (empty($orderIds)) {
            flash(translate("No orders selected."))->warning();
            return redirect()->back();
        }

        $orders = Order::whereIn('id', $orderIds)->get();

        if ($orders->isEmpty()) {
            flash(translate("No valid orders found."))->warning();
            return redirect()->back();
        }

        if (Session::has('currency_code')) {
            $currency_code = Session::get('currency_code');
        } else {
            $currency_code = Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if ($currency_code == 'BDT' || $language_code == 'bd') {
            $font_family = "'Hind Siliguri','freeserif'";
        } else {
            $font_family = "freeserif";
        }

        $pdf_style_data = [
            'font_family' => 'monospace',
            'direction'   => 'ltr',
            'text_align'  => 'left',
        ];

        $print_width = 100;

        $mpdf = null;

        foreach ($orders as $index => $order) {
            $html = view('backend.invoices.thermal_invoice', compact(
                'order',
                'font_family',
                'pdf_style_data'
            ))->render();

            if ($index === 0) {
                $mpdf = new \Mpdf\Mpdf([
                    'default_font' => 'monospace',
                    'mode'         => 'utf-8',
                    'format'       => [$print_width, 1000],
                ]);
                $mpdf->WriteHTML($html);

                $mpdf->page = 0;
                $mpdf->state = 0;
                unset($mpdf->pages[0]);
                $p = 'P';
                $mpdf->_setPageSize([$print_width, $mpdf->y], $p);
                $mpdf->addPage();
                $mpdf->WriteHTML($html);
            } else {
                $tempMpdf = new \Mpdf\Mpdf([
                    'default_font' => 'monospace',
                    'mode'         => 'utf-8',
                    'format'       => [$print_width, 1000],
                ]);
                $tempMpdf->WriteHTML($html);

                $tempMpdf->page = 0;
                $tempMpdf->state = 0;
                unset($tempMpdf->pages[0]);
                $p = 'P';
                $tempMpdf->_setPageSize([$print_width, $tempMpdf->y], $p);
                $tempMpdf->addPage();
                $tempMpdf->WriteHTML($html);

                $tempFile = storage_path('app/temp_' . uniqid() . '.pdf');
                $tempMpdf->Output($tempFile, 'F');

                $pageCount = $mpdf->setSourceFile($tempFile);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $mpdf->AddPage();
                    $mpdf->useTemplate($mpdf->importPage($i));
                }

                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        if ($mpdf) {
            $mpdf->Output('bulk_thermal_invoices.pdf', 'I');
        } else {
            flash(translate("No invoices generated."))->error();
            return redirect()->back();
        }
    }
}
