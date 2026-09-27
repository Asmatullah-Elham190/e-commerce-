<a href="{{ route('compare') }}" class="d-flex align-items-center" data-toggle="tooltip"
    data-title="{{ translate('Compare') }}" data-placement="top" style="color: {{ get_setting('top_header_text_color') }}">
    <span class="position-relative d-inline-block fs-12">
        {{ translate('Compare') }}
@if (Session::has('compare'))
    ({{ count(Session::get('compare')) }})
@endif
    </span>
</a>