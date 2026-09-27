<a href="{{ route('wishlists.index') }}" class="d-flex align-items-center " data-toggle="tooltip" data-title="{{ translate('Wishlist') }}" data-placement="top" style="color: {{ get_setting('top_header_text_color') }}">
    <span class="position-relative d-inline-block fs-12">
        {{ translate('Wishlist') }}
        @if(Auth::check())
            @php $wishlistProductCount = get_wishlists()->count(); @endphp
@if($wishlistProductCount > 0)
    ({{ $wishlistProductCount }})
@endif
        @endif
    </span>
</a>