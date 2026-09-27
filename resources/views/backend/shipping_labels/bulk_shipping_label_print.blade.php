
@foreach ($orders as $order)

    @include($view, [
        'order'          => $order,
        'font_family'    => $font_family,
        'direction'      => $direction,
        'text_align'     => $text_align,
        'not_text_align' => $not_text_align,
    ])
    @if (!$loop->last)
        <pagebreak />
    @endif
@endforeach