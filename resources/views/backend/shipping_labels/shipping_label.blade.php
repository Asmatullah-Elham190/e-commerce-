<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>Shipping Label - {{ $order->code }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: <?php echo ($label_width ?? '4in') ?> <?php echo ($label_height ?? '6in') ?>;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: <?php echo $font_family ?>;
            direction: <?php echo $direction ?>;
            background: #fff;
            color: #111;
            font-size: 12px;
            line-height: 1.45;
        }

        .label-wrapper {
            width: 100%;
            background: #fff;
        }
        .label-box {
            border: 2px solid #111;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            width: <?php echo ($label_width ?? '4in') ?>;
            min-height: <?php echo ($label_height ?? '6in') ?>;
            max-width: 100%;
        }
        .divider {
            border-top: 1.5px solid #111;
        }

        .header {
            text-align: center;
            padding: 10px 12px 10px;
            overflow: hidden;
        }
        .header img {
            display: block;
            margin: 0 auto 10px;
            object-fit: cover; 
        }
        .header .site-name {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .header .order-id {
            font-size: 12px;
            color: #444;
            margin-top: 2px;
        }

        .barcode-strip {
            padding: 8px 10px 4px;
            text-align: center;
            overflow: hidden;
        }
        .barcode-strip svg {
            display: block;
            margin: 0 auto;
            max-width: 100%;
        }
        .barcode-text {
            font-size: 12px;
            letter-spacing: 2px;
            font-weight: 700;
            font-family: monospace;
            color: #222;
            margin-top: 3px;
            text-align: center;
        }

        .qr-meta-row {
            width: 100%;
            text-align: center; 
        }

        .qr-meta-row table {
            margin: 0 auto; 
            border-collapse: collapse;
        }

        .qr-meta-row td {
            vertical-align: middle;
            padding: 4px 5px;
        }
        .qr-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 4px 5px;
            width: 88px;
            border-right: 1.5px solid #111;
            text-align: center;
        }
        .qr-cell svg {
            width: 68px;
            height: 68px;
            display: block;
            margin: 0 auto;
        }
        .meta-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 4px 6px;
        }
        .meta-line {
            font-size: 12px;
            margin-bottom: 4px;
            color: #222;
        }
        .meta-line strong {
            font-weight: 700;
        }

        .meta-info-table {
            width: auto; 
            margin: 0 auto; 
            border-collapse: collapse;
        }
        .meta-info-table td {
            padding: 1px 0;
            font-size: 12px;
            line-height: 1.2;
            vertical-align: middle; 
            text-align: left; 
        }
        .meta-label {
            font-weight: 700;
            width: 65px; 
            text-transform: uppercase; 
        }
        .meta-colon {
            width: 15px;
            text-align: center;
        }

        .address-wrap {
            padding: 0 5px 5px;
            height: 100px !important;
            overflow: hidden;
        }
        .address-box {
            margin-top: 4px;
        }
        .addr-name {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 2px;
        }
        .addr-line {
            font-size: 12px;
            margin-bottom: 2px;
            color: #111;
            
        }
        .addr-line strong {
            font-weight: 700;
        }

        .cod-wrap {
            padding: 0 8px 8px;
        }
        .cod-table {
            width: 100%;
            overflow: hidden;
        }
        .cod-table td {
            padding: 6px 8px;
            vertical-align: middle;
            font-weight: 800;
        }
        .label-part {
            font-size: 18px;
            border-right: 1.5px solid #111; 
            width: 40%;
        }
        .amount-part {
            font-size: 20px;
            text-align: right;
        }
        .amount-part-paid {
            font-size: 20px;
            text-align: center;
        }

        .footer {
            display: table;
            width: 100%;
            padding: 4px 6px;
        }
        .footer-left {
            display: table-cell;
            vertical-align: bottom;
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }
        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: <?php echo $not_text_align ?>;
        }
        .footer-right img {
            max-height: 20px;
            max-width: 80px;
            display: block;
            margin-<?php echo $direction == 'ltr' ? 'left' : 'right' ?>: auto;
            margin-bottom: 2px;
        }
        .footer-sitename {
            font-size: 12px;
            font-weight: 900;
        }
        .footer-url {
            font-size: 12px;
            color: #666;
        }
        .custom-footer {
            text-align: center;
            padding: 2px 5px;
            font-size: 12px;
            color: #666;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="label-wrapper">
<div class="label-box">

    @php
        $sl      = json_decode(get_setting('shipping_label'), true) ?? [];
        $flds    = $sl['fields'] ?? [];
        $btype   = $sl['barcode_type']   ?? 'qrcode';
        $bencode = $sl['barcode_encode'] ?? 'order_number';
        $showTxt = $sl['show_human_readable_text_below_barcode'] ?? 0;
        $showQR  = $sl['show_qr_code_alongside_barcode'] ?? 0;
        $logo    = $sl['label_logo'] ?? null;
        $sender_name_and_address  = $sl['sender_name_and_address'] ?? 0;
        $show_tracking_code  = $flds['show_tracking_code'] ?? 0;

        $shipping = json_decode($order->shipping_address);

        if ($bencode == 'custom_value')
            $bval = $sl['custom_barcode_value'] ?? $order->code;
        elseif ($bencode == 'tracking_code')
            $bval = $order->tracking_code;
        else
            $bval = $order->code;

        $sender_name = $order->shop->name ?? get_setting('site_name');
        $removedXML  = '<?xml version="1.0" encoding="UTF-8"?>';

        $code39map = [
            '0'=>'101001101101','1'=>'110100101011','2'=>'101100101011',
            '3'=>'110110010101','4'=>'101001101011','5'=>'110100110101',
            '6'=>'101100110101','7'=>'101001011011','8'=>'110100101101',
            '9'=>'101100101101','A'=>'110101001011','B'=>'101101001011',
            'C'=>'110110100101','D'=>'101011001011','E'=>'110101100101',
            'F'=>'101101100101','G'=>'101010011011','H'=>'110101001101',
            'I'=>'101101001101','J'=>'101011001101','K'=>'110101010011',
            'L'=>'101101010011','M'=>'110110101001','N'=>'101011010011',
            'O'=>'110101101001','P'=>'101101101001','Q'=>'101010110011',
            'R'=>'110101011001','S'=>'101101011001','T'=>'101011011001',
            'U'=>'110010101011','V'=>'100110101011','W'=>'110011010101',
            'X'=>'100101101011','Y'=>'110010110101','Z'=>'100110110101',
            '-'=>'100101011011','.'=>'110010101101',' '=>'100110101101',
            '$'=>'100100100101','/'=>'100100101001','+'=>'100101001001',
            '%'=>'101001001001','*'=>'100101101101',
        ];
        $c128map = [
            ' '=>'212222','!'=>'222122','"'=>'222221','#'=>'121223','$'=>'121322',
            '%'=>'131222','&'=>'122213',"'"=>'122312','('=>'132212',')'=>'221213',
            '*'=>'221312','+'=>'231212',','=>'112232','-'=>'122132','.'=>'122231',
            '/'=>'113222','0'=>'123122','1'=>'123221','2'=>'223211','3'=>'221132',
            '4'=>'221231','5'=>'213212','6'=>'223112','7'=>'312131','8'=>'311222',
            '9'=>'321122',':'=>'321221',';'=>'312212','<'=>'322112','='=>'322211',
            '>'=>'212123','?'=>'212321','@'=>'232121','A'=>'111323','B'=>'131123',
            'C'=>'131321','D'=>'112313','E'=>'132113','F'=>'132311','G'=>'211313',
            'H'=>'231113','I'=>'231311','J'=>'112133','K'=>'112331','L'=>'132131',
            'M'=>'113123','N'=>'113321','O'=>'133121','P'=>'313121','Q'=>'211331',
            'R'=>'231131','S'=>'213113','T'=>'213311','U'=>'213131','V'=>'311123',
            'W'=>'311321','X'=>'331121','Y'=>'312113','Z'=>'312311','['=>'332111',
            '\\'=>'314111',']'=>'221411','^'=>'431111','_'=>'111224','`'=>'111422',
            'a'=>'121124','b'=>'121421','c'=>'141122','d'=>'141221','e'=>'112214',
            'f'=>'112412','g'=>'122114','h'=>'122411','i'=>'142112','j'=>'142211',
            'k'=>'241211','l'=>'221114','m'=>'413111','n'=>'241112','o'=>'134111',
            'p'=>'111242','q'=>'121142','r'=>'121241','s'=>'114212','t'=>'124112',
            'u'=>'124211','v'=>'411212','w'=>'421112','x'=>'421211','y'=>'212141',
            'z'=>'214121','{'=>'412121','|'=>'111143','}'=>'111341','~'=>'131141',
        ];

        $makeSvg = function(string $value, string $type) use ($code39map, $c128map): string {
            $h = 100; $quiet = 8; $u = 2;
            $v = strtoupper($value);

            if ($type === 'code39') {
                $encoded = '*' . $v . '*';
                $bars = []; $x = $quiet;
                foreach (str_split($encoded) as $ci => $ch) {
                    if (!isset($code39map[$ch])) continue;
                    if ($ci > 0) $x += $u;
                    foreach (str_split($code39map[$ch]) as $bi => $bit) {
                        $w = ($bit === '1') ? $u * 2 + 1 : $u;
                        if ($bi % 2 === 0) $bars[] = ['x' => $x, 'w' => $w];
                        $x += $w;
                    }
                }
                $tw = $x + $quiet;
                $r = '';
                foreach ($bars as $b) $r .= "<rect x=\"{$b['x']}\" y=\"0\" width=\"{$b['w']}\" height=\"{$h}\" fill=\"#000\"/>";
                return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$tw} {$h}\" style=\"display:block;max-width:100%;height:{$h}px;\">{$r}</svg>";
            }

            if ($type === 'code128') {
                $bars = []; $x = $quiet; $isBar = true;
                $dp = function(string $pat) use (&$bars, &$x, &$isBar, $u, $h) {
                    foreach (str_split($pat) as $d) {
                        $w = (int)$d * $u;
                        if ($isBar && $w > 0) $bars[] = ['x' => $x, 'w' => $w];
                        $x += $w; $isBar = !$isBar;
                    }
                };
                $dp('2114');
                $ck = 104;
                foreach (str_split($value) as $idx => $ch) {
                    $ck += ($idx + 1) * (ord($ch) - 32);
                    if (isset($c128map[$ch])) $dp($c128map[$ch]);
                }
                $ck %= 103;
                $cc = chr($ck + 32);
                if (isset($c128map[$cc])) $dp($c128map[$cc]);
                $dp('2331112');
                $tw = $x + $quiet;
                $r = '';
                foreach ($bars as $b) $r .= "<rect x=\"{$b['x']}\" y=\"0\" width=\"{$b['w']}\" height=\"{$h}\" fill=\"#000\"/>";
                return "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 {$tw} {$h}\" style=\"display:block;max-width:100%;height:{$h}px;\">{$r}</svg>";
            }

            return "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"200\" height=\"{$h}\"><rect width=\"200\" height=\"{$h}\" fill=\"#eee\"/><text x=\"100\" y=\"30\" text-anchor=\"middle\" font-size=\"14\" fill=\"#555\">{$value}</text></svg>";
        };
    @endphp

    <div class="header">
        @if($logo)
            <img src="{{ uploaded_asset($logo) }}" alt="logo" height="48" style="display:inline-block;">
        @else
            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="logo" height="30"  style="display:inline-block;">
        @endif
        <div class="site-name"> <strong>{{ get_setting('site_name') }}</strong> </div>
        @if(!empty($flds['order_number']))
            <div class="order-id">ORDER ID: {{ $order->code }}</div>
        @endif
    </div>

    <div class="divider"></div>
    <div class="barcode-strip">
        @if($btype === 'qrcode')
            {!! str_replace($removedXML, '', QrCode::size(90)->generate($bval)) !!}
        @else
            {!! $makeSvg($bval, $btype) !!}
        @endif
        @if($showTxt)
            <div class="barcode-text">{{ $bval }}</div>
        @endif
    </div>

    <div class="divider"></div>
    <div class="qr-meta-row">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
            <tr>
                @if($showQR && $btype !== 'qrcode')
                    <td style="border-right: 1.5px solid #111; width: 88px; text-align: center; vertical-align: middle;">
                        <div style="width: 68px; margin: 0 auto;">
                            {!! str_replace($removedXML, '', QrCode::size(68)->generate($bval)) !!}
                        </div>
                    </td>
                @endif
                
                <td style="text-align: center; padding: 8px 12px; vertical-align: middle;">
                    <table class="meta-info-table">
                        @if(!empty($flds['order_number']))
                            <tr>
                                <td class="meta-label">INVOICE</td>
                                <td class="meta-colon">:</td>
                                <td>{{ $order->code }}</td>
                            </tr>
                        @endif
                        
                        @if($order->shipping_type)
                            <tr>
                                <td class="meta-label">D. Type</td>
                                <td class="meta-colon">:</td>
                                <td>
                                    @if($order->shipping_type == 'home_delivery') Home
                                    @elseif($order->shipping_type == 'pickup_point') Pickup
                                    @elseif($order->shipping_type == 'carrier') {{ $order->carrier->name ?? 'Carrier' }}
                                    @else N/A
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if(!empty($flds['sender_name_and_address']))
                            <tr>
                                <td class="meta-label">From</td>
                                <td class="meta-colon">:</td>
                                <td>{{ $sender_name }}</td>
                            </tr>
                            <tr>
                                <td class="meta-label">Address</td>
                                <td class="meta-colon">:</td>
                                <td>{{ get_seller_address($order) }}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </div>

    @if(!empty($flds['receiver_name_and_address']))
    <div class="divider"></div>
    <div class="address-wrap">
        <div class="address-box">
            <div class="addr-name">{{ $shipping->name }}</div>
            @if(!empty($shipping->phone))
                <div class="addr-line"><strong>{{ translate('Phone') }}:</strong> {{ $shipping->phone }}</div>
            @endif
            <div class="addr-line">
                <strong>{{ translate('Address') }}:</strong>
                {{ $shipping->address ? $shipping->address.', ' : '' }}{{ $shipping->city ? $shipping->city.', ' : '' }}{{ !empty($shipping->state) ? $shipping->state.', ' : '' }}{{ $shipping->postal_code ?? '' }}{{ $shipping->country ? ', '.$shipping->country : '' }}
            </div>
        </div>
    </div>
    @endif

    @if(!empty($flds['cod_amount']))
    <div class="divider"></div>
    <div class="cod-wrap" style="margin-top: 10px;">
        <table class="cod-table">
            <tr>
                @if($order->payment_type == 'cash_on_delivery')
                    <td class="label-part"><strong>COD</strong></td>
                    <td class="amount-part">
                            <strong>{{ single_price($order->grand_total) }}</strong>
                    </td>
                @else
                    <td class="amount-part-paid">
                        <span style="font-size:20px;"><strong>PAID</strong></span>
                    </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <div class="divider"></div>
    <div class="footer">
        <div class="footer-left">
            <div>{{ translate('Date') }}: {{ date('d/m/y', $order->date) }}</div>
            <div>{{ date('h:ia', $order->date) }}</div>
        </div>
        <div class="footer-right">
            <div class="footer-sitename">{{ env('APP_NAME') }}</div>
            @php $siteUrl = env('APP_URL') @endphp
            @if($siteUrl)
                <div class="footer-url">{{ $siteUrl }}</div>
            @endif
        </div>
    </div>

    @if(!empty($flds['custom_footer_text']) && !empty($sl['custom_footer_text']))
    <div class="divider"></div>
    <div class="custom-footer">{{ $sl['custom_footer_text'] }}</div>
    @endif

</div>
</div>
</body>
</html>