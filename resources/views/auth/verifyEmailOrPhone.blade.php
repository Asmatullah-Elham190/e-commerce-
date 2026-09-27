<script type="text/javascript">
    function sendVerificationCode(clickedBtn = null) {
        let recaptchaEnabled = {{ get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1 ? 'true' : 'false' }};

        let btn = clickedBtn ? clickedBtn : document.getElementById('sendOtpBtn');
        let email = $('#signinSrEmail').length ? $('#signinSrEmail').val() : $('#signinAddonEmail').val();
        let phone = $('#phone-code').length ? $('#phone-code').val() : '';
        let country_code = $('input[name="country_code"]').val() ?? '';

        let identifier = email ? email : phone;
        let verify_field = email ? 'email' :'phone';
        if (!identifier) {
            AIZ.plugins.notify('danger', '{{ translate("Please enter your email or phone number") }}');
            return;
        }

        let emailPhoneDiv = $('#emailOrPhoneDiv');
        let codeGroup = $('#verification_code').closest('.form-group');
        let originalText = $(btn).html();

        $(btn).prop('disabled', true).text('Sending...');

        function ajaxSend(data) {
            $.post('{{ route("customer-reg.verification_code_send") }}', data, function (res) {

                if (res.status == 1) {
                    AIZ.plugins.notify('success', res.message);
                    //emailPhoneDiv.addClass('d-none');
                    $(`.${verify_field}-form-group`).addClass('d-none');
                    $('#mail_phone_toggle_btn').length && $('#mail_phone_toggle_btn').addClass('d-none');
                    codeGroup.removeClass('d-none').addClass('d-block');

                } else if (res.status == 2) {
                    AIZ.plugins.notify('danger', res.message);

                } else {
                    AIZ.plugins.notify('danger', res.message);
                }

            }).fail(function () {
                AIZ.plugins.notify('danger', 'Something went wrong');
            }).always(function () {
                $(btn).prop('disabled', false).html(originalText);
            });
        }

        let postData = {
            _token: '{{ csrf_token() }}',
            email: email,
            phone: phone,
            country_code: country_code
        };

        if (recaptchaEnabled) {
            grecaptcha.ready(function () {
                grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {
                    action: 'verification_code_send'
                }).then(function (token) {
                    postData['g-recaptcha-response'] = token;
                    ajaxSend(postData);
                });
            });
        } else {
            ajaxSend(postData);
        }
    }



    const codeInput = document.getElementById('verification_code');
    const verifyBtn = document.getElementById('verifyOtpBtn');

    //realtime validation
    codeInput.addEventListener('input', function() {
        
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 6) this.value = this.value.slice(0,6);

        if (this.value.length === 6) {
            verifyBtn.innerHTML = '<i class="las la-lg la-spinner la-spin"></i>';

            let email = $('#signinSrEmail').length ? $('#signinSrEmail').val() : $('#signinAddonEmail').val();
            let phone = $('#phone-code').length ? $('#phone-code').val() : '';
            let country_code = $('input[name="country_code"]').val()?? '';
            let identifier = email ? email : phone;
            $.post('{{ route("customer-reg.verify_code_confirmation") }}', {
                _token: '{{ csrf_token() }}',
                verification_code: this.value,
                email: email,  
                phone: phone  ,
                country_code: country_code
            }, function(data) {
                if(data.status === 1){
                    verifyBtn.innerHTML = '<i class="las la-lg la-check-circle text-success"></i>';
                    AIZ.plugins.notify('success', `${data.message}`);
                    codeInput.disabled = true;
                    verifyBtn.classList.add('disabled');
                    verifyBtn.style.backgroundColor = '#f7f8fa';
                     toggleCreateBtn();

                } else {
                    AIZ.plugins.notify('danger', `${data.message}`);
                    verifyBtn.innerHTML = '<i class="las la-lg la-times-circle text-danger"></i>';
                     toggleCreateBtn();
                }
            });
        } else {
            verifyBtn.innerHTML = '<i class="las la-lg la-arrow-right"></i>';
        }
    });

   
</script>