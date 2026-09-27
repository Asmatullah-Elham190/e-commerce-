@extends('backend.layouts.app')

@section('content')

    <div class="row seller-page">
        <div class="col-md-12">
            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center pb-3 mx-4">
                <h5 class="mb-2 mb-lg-0 font-weight-bold">{{ translate('Select Font Family') }}</h5>
            </div>

            <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="types[]" value="system_font_family">

                <div class="row mx-1 font-family-card" style="margin-bottom: 4rem !important;"></div>

                <div class="aiz-bottom-bar px-15px px-lg-25px bg-white">
                    <div class="text-center mt-3 mb-2" id="view-more-container">
                        @if(count($fonts) > 20)
                            <button type="button" class="btn btn-outline-primary"
                                id="view-more-btn">{{ translate('Load More') }}...</button>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xl-8" style="padding-left: 2rem !important;">
                            <button class="btn bg-blue-color2 text-primary w-100">
                                <small class="font-weight-bold">
                                    {{ translate('You have selected') }} <span id="dynamic-text"> ... </span>
                                </small>
                            </button>
                        </div>
                        <div class="col-xl-4 mt-2 mt-xl-0" style="padding-right: 2rem !important;">
                            <button class="btn btn-primary w-100" type="submit">{{ translate('SET FONT FAMILY') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            if (typeof $ === 'undefined') {
                console.error('jQuery not found. Make sure jQuery is loaded before this script.');
                return;
            }

            let fonts = @json(array_values($fonts ?? []));
            const selectedFont = @json($selectedFont ?? '');
            const container = $('.font-family-card');
            const viewMoreBtn = $('#view-more-btn');

            console.log('fonts count:', fonts.length, 'selectedFont:', selectedFont);

            let startIndex = 0;
            const limit = 20;

            if (selectedFont && fonts.includes(selectedFont)) {
                fonts = [selectedFont, ...fonts.filter(f => f !== selectedFont)];
            }

            container.empty();

            if (!viewMoreBtn.length || fonts.length <= limit) {
                if (viewMoreBtn.length) viewMoreBtn.hide();
            }

            renderFonts();

            if (viewMoreBtn.length) {
                viewMoreBtn.on('click', function () {
                    renderFonts();
                });
            }

            function renderFonts() {
                const slice = fonts.slice(startIndex, startIndex + limit);
                if (!slice.length) {
                    if (viewMoreBtn.length) viewMoreBtn.hide();
                    return;
                }

                let html = '';
                slice.forEach(font => {
                    const safeFont = String(font).replace(/"/g, '&quot;');
                    const isSelected = (font === selectedFont);
                    html += `
                        <div class="col-md-6 col-lg-3 d-flex font-card">
                            <div class="card text-center px-3 py-4 w-100 ${isSelected ? 'border border-primary border-2' : ''}" data-font_family="${safeFont}">
                                <div class="text-left mx-4">
                                    <div class="d-flex align-items-left justify-content-left mb-2">
                                        <input type="radio" hidden class="mr-2" name="system_font_family" value="${safeFont}" ${isSelected ? 'checked' : ''}>
                                        <label class="mb-0 font-weight-bold" style="font-family: ${safeFont} !important;">${safeFont}</label>
                                    </div>
                                    <p class="text-muted mb-0" style="font-size: 12px; font-family: ${safeFont} !important;">
                                        It is a long established fact that a reader will be distracted by the readable by their own.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                container.append(html);
                startIndex += limit;

                if (startIndex >= fonts.length) {
                    viewMoreBtn.hide();
                } else {
                    viewMoreBtn.show();
                }

                updateSelectedText();
            }

            container.on('click', '.card', function (e) {
                if (!$(e.target).is('input[type=radio]')) {
                    $(this).find('input[type=radio]').prop('checked', true).trigger('change');
                }
            });

            container.on('change', 'input[name="system_font_family"]', function () {
                $('.font-family-card .card').removeClass('border border-primary border-2');
                $(this).closest('.card').addClass('border border-primary border-2');
                updateSelectedText();
            });

            function updateSelectedText() {
                const selected = $('input[name="system_font_family"]:checked');
                if (selected.length) {
                    const fontName = selected.closest('.card').data('font_family');
                    $('#dynamic-text').text(fontName);
                } else {
                    $('#dynamic-text').text('...');
                }
            }

            setTimeout(() => {
                if ($('input[name="system_font_family"]:checked').length === 0 && selectedFont) {
            
                    const matched = container.find(`.card[data-font_family="${selectedFont}"]`);
                    if (matched.length) {
                        matched.find('input[name="system_font_family"]').prop('checked', true).trigger('change');
                    }
                }
            }, 250);
        });
    </script>
@endsection



