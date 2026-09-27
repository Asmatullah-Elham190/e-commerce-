@foreach($notes as $note)
    <div class="carousel-box">
        <div class="{{ $class }} border border-2 border-gray-300 rounded-2 p-3 overflow-hidden has-transition"
             data-note-id="{{ $note->id }}"
             onclick="selectNote(this, '{{ $hiddenInput }}', '{{ $class }}', {{ $note->id }})">
            <p class="fs-14 fw-400 m-0 text-truncate-3">
                {{ $note->getTranslation('description') }}
            </p>
        </div>
    </div>
@endforeach