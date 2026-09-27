@if ($last > 1)
<nav>
    <ul class="pagination">
        {{-- Previous Page --}}
        @if ($current == 1)
            <li class="page-item disabled"><span class="page-link">
                 <i class="las la-angle-left fs-20 fw-600 position_middle"></i>
            </span></li>
        @else
            <li class="page-item">
                <a class="page-link page-btn " style="font-size: 20px;" href="#" data-page="{{ $current - 1 }}">
                     <i class="las la-angle-left fs-20 fw-600 position_middle"></i>
                </a>
            </li>
        @endif

        {{-- First page --}}
        @if ($current > 4)
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="1">1</a>
            </li>
            @if ($current > 5)
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif
        @endif

        {{-- Middle pages (3 before and 3 after current) --}}
        @for ($i = max(1, $current - 3); $i <= min($last, $current + 3); $i++)
            @if ($i == $current)
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item">
                    <a class="page-link page-btn" href="#" data-page="{{ $i }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        {{-- Last page --}}
        @if ($current < $last - 3)
            @if ($current < $last - 4)
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="{{ $last }}">{{ $last }}</a>
            </li>
        @endif

        {{-- Next Page --}}
        @if ($current < $last)
            <li class="page-item">
                <a class="page-link page-btn" href="#" data-page="{{ $current + 1 }}">
                    <i class="las la-angle-right fs-20 fw-600 position_middle"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="las la-angle-right fs-20 fw-600 position_middle"></i>
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif
