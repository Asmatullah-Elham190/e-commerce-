<html>
<head>
    <style type="text/css">
        * { margin: 0; padding: 0; }
        html, body { overflow: hidden; }
    </style>
    <script src='https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}'></script>
</head>
<body>
    <form id="captcha-form">
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            executeCaptcha();
        });

        function executeCaptcha() {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ env('CAPTCHA_KEY') }}', {action: 'submit'})
                    .then(function(token) {
                        if (typeof Captcha !== 'undefined') {
                            console.log("reCAPTCHA v3 token:", token);
                            Captcha.postMessage(token);
                        }
                        setTimeout(executeCaptcha, 120000);
                    })
                    .catch(function(error) {
                        console.error("reCAPTCHA v3 error:", error);
                        if (typeof Captcha !== 'undefined') {
                            Captcha.postMessage("error");
                        }
                    });
            });
        }

        function captchaShow() {
            if (typeof CaptchaShowValidation !== 'undefined') {
                CaptchaShowValidation.postMessage(true);
            }
        }
        setInterval(captchaShow, 4000);
    </script>
</body>
</html>