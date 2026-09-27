@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '-';
    }
@endphp

<li  d-item="{{ $childCategory->products_count }}" id="preorder_{{ $childCategory->id }}">{{ $value }}
    {{ $childCategory->getTranslation('name') }}
    @if($childCategory->products_count > 0)
        {{ "   (". $childCategory->products_count . ")" }}
    @endif
</li>

@if ($child_category->childrenCategories)
    @foreach ($child_category->childrenCategories as $childCategory)
        @include('frontend.product_listing_page_child_category_preorder', ['child_category' => $childCategory])
    @endforeach
@endif
