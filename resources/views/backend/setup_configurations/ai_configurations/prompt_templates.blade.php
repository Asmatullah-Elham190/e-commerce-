@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Prompt Templates')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-10 mx-auto">
                           @foreach ($prompt_templates as $key => $prompt_template)
                                <div class="prompt-template">
                                    <form action="{{ route('ai-prompt-templates.update', encrypt($prompt_template->id)) }}" method="POST">
                                        <input name="_method" type="hidden" value="PATCH">
                                        @csrf
                                        <div class="form-group">
                                            <label class="col-form-label">
                                                {{ translate(ucwords(str_replace('_', ' ', $prompt_template->identifier))) }}
                                            </label>

                                            <textarea name="prompt" class="form-control prompt-textarea" rows="6" readonly required >{{ $prompt_template->prompt }}</textarea>

                                            <small class="form-text text-danger">
                                                **N.B : Do Not Change The Variables Like { ____ }.**
                                            </small>
                                        </div>
                                        <div class="form-group mb-0 text-right">
                                            <button type="button" class="btn btn-primary edit-btn">
                                                {{ translate('Edit') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

let currentEditing = null;

document.querySelectorAll('.edit-btn').forEach(button => {

    button.addEventListener('click', function () {

        const container = this.closest('.prompt-template');
        const textarea = container.querySelector('.prompt-textarea');

        // If another textarea is active → reset it
        if (currentEditing && currentEditing !== textarea) {
            currentEditing.setAttribute('readonly', true);

            let oldBtn = currentEditing.closest('.prompt-template').querySelector('.edit-btn');
            oldBtn.innerText = 'Edit';

            currentEditing = null;
        }

        // If currently locked → unlock
        if (textarea.hasAttribute('readonly')) {

            textarea.removeAttribute('readonly');
            textarea.focus();
            this.innerText = 'Save';

            currentEditing = textarea;

        } else {

            // submit form
            container.querySelector('form').submit();
        }

    });

});

</script>
@endsection
