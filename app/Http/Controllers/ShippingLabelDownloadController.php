<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use PDF;
use Config;
use ZipArchive;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShippingLabelDownloadController extends Controller
{
    private function getFontAndDirection(): array
    {
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

        return compact('currency_code', 'language_code', 'direction', 'text_align', 'not_text_align', 'font_family');
    }

    private function getPresetConfig(): array
    {
        $shipping_label = json_decode(get_setting('shipping_label'), true) ?? [];
        $preset         = $shipping_label['label_size_preset'] ?? '4x6';

        $labelSizes = [
            '2x3' => ['width' => 57,  'height' => 85],
            '3x4' => ['width' => 85,  'height' => 113],
            '4x4' => ['width' => 113, 'height' => 113],
            '4x6' => ['width' => 113, 'height' => 170],
        ];

        $size = $labelSizes[$preset] ?? $labelSizes['4x6'];

        $pdfConfig = [
            'format'      => [$size['width'], $size['height']],
            'orientation' => 'portrait',
        ];

        if ($preset === '2x3') {
            $view = 'backend.shipping_labels.shipping_label_mini';
        } elseif (in_array($preset, ['3x4', '4x4'])) {
            $view = 'backend.shipping_labels.shipping_label_small';
        } else {
            $view = 'backend.shipping_labels.shipping_label';
        }

        return compact('preset', 'pdfConfig', 'view');
    }

    public function shipping_label_download($id)
    {
        $font      = $this->getFontAndDirection();
        $config    = $this->getPresetConfig();
        $order     = Order::findOrFail($id);

        if (
            in_array(auth()->user()->user_type, ['admin', 'staff']) ||
            in_array(auth()->id(), [$order->user_id, $order->seller_id])
        ) {
            return PDF::loadView($config['view'], [
                'order'          => $order,
                'font_family'    => $font['font_family'],
                'direction'      => $font['direction'],
                'text_align'     => $font['text_align'],
                'not_text_align' => $font['not_text_align'],
            ], [], $config['pdfConfig'])->download('order-' . $order->code . '.pdf');
        }

        flash(translate("You do not have the right permission to access this invoice."))->error();
        return redirect()->route('home');
    }

    public function shipping_label_printer($id)
    {
        $font = $this->getFontAndDirection();
        $config = $this->getPresetConfig();
        $order = Order::findOrFail($id);

        $pdf = PDF::loadView($config['view'], [
            'order'          => $order,
            'font_family'    => $font['font_family'],
            'direction'      => $font['direction'],
            'text_align'     => $font['text_align'],
            'not_text_align' => $font['not_text_align'],
        ], [], $config['pdfConfig']);

        return $pdf->stream('order-' . $order->code . '.pdf');
    }
    public function bulk_shipping_label_download(Request $request)
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

        $font   = $this->getFontAndDirection();
        $config = $this->getPresetConfig();

        $tempDir = storage_path('app/temp_shipping_labels_' . auth()->id() . '_' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . '/shipping_labels.zip';
        $zip     = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            flash(translate("Could not create ZIP file. Please try again."))->error();
            return redirect()->back();
        }

        foreach ($orders as $order) {
            try {
                $pdfContent = PDF::loadView($config['view'], [
                    'order'          => $order,
                    'font_family'    => $font['font_family'],
                    'direction'      => $font['direction'],
                    'text_align'     => $font['text_align'],
                    'not_text_align' => $font['not_text_align'],
                ], [], $config['pdfConfig'])->output();

                $zip->addFromString('order-' . $order->code . '.pdf', $pdfContent);
            } catch (\Exception $e) {
                \Log::error("Shipping label PDF generation failed for order #{$order->id}: " . $e->getMessage());
            }
        }

        $zip->close();

        $fileName = 'shipping_labels_' . now()->format('Ymd_His') . '.zip';

        register_shutdown_function(function () use ($tempDir) {
            if (is_dir($tempDir)) {
                @rmdir($tempDir);
            }
        });

        return response()->download($zipPath, $fileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    public function shipping_label_print($id)
    {
        $order = Order::findOrFail($id);

        $language_code  = Session::get('locale', Config::get('app.locale'));
        $direction      = Language::where('code', $language_code)->first()->rtl == 1 ? 'rtl' : 'ltr';
        $text_align     = $direction == 'rtl' ? 'right' : 'left';
        $not_text_align = $direction == 'rtl' ? 'left' : 'right';

        $shipping_label = json_decode(get_setting('shipping_label'), true) ?? [];
        $preset         = $shipping_label['label_size_preset'] ?? '4x6';

        $labelSizes = [
            '2x3' => ['width' => '2in', 'height' => '3in'],
            '3x4' => ['width' => '3in', 'height' => '4in'],
            '4x4' => ['width' => '4in', 'height' => '4in'],
            '4x6' => ['width' => '4in', 'height' => '6in'],
        ];

        $labelSize = $labelSizes[$preset] ?? $labelSizes['4x6'];

        if ($preset === '2x3') {
            $view = 'backend.shipping_labels.shipping_label_mini';
        } elseif (in_array($preset, ['3x4', '4x4'])) {
            $view = 'backend.shipping_labels.shipping_label_small';
        } else {
            $view = 'backend.shipping_labels.shipping_label';
        }

        return view($view, [
            'order'          => $order,
            'font_family'    => "'Roboto', sans-serif",
            'direction'      => $direction,
            'text_align'     => $text_align,
            'not_text_align' => $not_text_align,
            'label_width'    => $labelSize['width'],
            'label_height'   => $labelSize['height'],
        ]);
    }

    public function bulk_shipping_label_print(Request $request)
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

        $font   = $this->getFontAndDirection();
        $config = $this->getPresetConfig();

        $mpdf = new \Mpdf\Mpdf($config['pdfConfig']);
        
        foreach ($orders as $index => $order) {
            if ($index > 0) {
                $mpdf->AddPage();
            }
            
            $html = view($config['view'], [
                'order'          => $order,
                'font_family'    => $font['font_family'],
                'direction'      => $font['direction'],
                'text_align'     => $font['text_align'],
                'not_text_align' => $font['not_text_align'],
            ])->render();
            
            $mpdf->WriteHTML($html);
        }
        
        return $mpdf->Output('bulk_shipping_labels.pdf', 'I');
    }
}