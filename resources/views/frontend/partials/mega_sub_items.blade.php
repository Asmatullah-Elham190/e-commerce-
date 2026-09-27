@foreach ($categories as $cat)
    @php
        $cat_name = $cat->getTranslation('name');
        $children = $cat->childrenCategories ?? collect();
    @endphp

    <li class="mb-2 fs-14 " style="padding-left: {{ $depth * 12 }}px">
       <a class="{{ $depth == 0 ? 'fw-600' : '' }} text-dark hov-text-primary animate-underline-primary"
            href="{{ route('products.category', $cat->slug) }}">
            {{ $cat_name }}
        </a>
    </li>

    @if ($children->count())
        @include('frontend.partials.mega_sub_items', ['categories' => $children, 'depth' => $depth + 1])
    @endif
@endforeach