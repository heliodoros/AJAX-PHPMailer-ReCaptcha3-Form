<!DOCTYPE HTML>
<html lang="el">

<head>
    <title>Ajax PHPMailer ReCaptcha3 JQueryValidate Form</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="description" content="Ajax PHPMailer ReCaptcha JQueryValidate Form">
    <meta name="keywords" content="contact, form, recaptcha, secure, ajax">
</head>

<body>
    <article id="contact">
        <header>
            <h2>Ajax PHPMailer ReCaptcha3 JQueryValidate Form</h2>
        </header>

        <form name="frmContact" id="frmContact">
            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
            <div>
                <div>
                    <div>
                        <input type="text" name="name" id="name" placeholder="Name" />
                    </div>
                    <div>
                        <input type="text" name="email" id="email" placeholder="Email" />
                    </div>
                    <div>
                        <input type="text" name="subject" id="subject" placeholder="Subject" />
                    </div>
                    <div>
                        <textarea name="message" id="message" placeholder="Message" rows="6"></textarea>
                    </div>
                    <div>
                        <input type="submit" name="btnContact" id="button" value="Send Message" />
                    </div>
                    <div>
                        <small>This site is protected by reCAPTCHA and the Google
                            <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                            <a href="https://policies.google.com/terms">Terms of Service</a> apply.</small>
                    </div>
                    <div>
                        <small>By submitting this form you agree that your personal information will be processed by the website</small>
                    </div>
                </div>
            </div>
        </form>
    </article>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script async src="https://www.google.com/recaptcha/api.js?render=RECAPTCHA_KEY_GOES_HERE"></script>
    <script>
        $(document).ready(function() {
            $("#frmContact").validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    subject: {
                        required: true
                    },
                    message: {
                        required: true
                    }
                },

                messages: {},
                submitHandler: function(form) {
                    document.getElementById("button").disabled = true;
                    var thankyoudiv = '<div><center><h2>Thank You!</h2></center></div>';
                    var errordiv = '<div><center><h2>There was an error. Please try again later</h2></center></div>';
                    try {
                        grecaptcha.ready(function() {
                        grecaptcha.execute('RECAPTCHA_KEY_GOES_HERE', {
                            action: 'contact'
                        }).then(function(token) {
                            var recaptchaResponse = document.getElementById('recaptchaResponse');
                            recaptchaResponse.value = token;
                            var fomr = $('form')[0];
                            var formData = new FormData(fomr);
                            $.ajax({
                                type: 'POST',
                                url: "sendmail.php",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(data) {
                                    console.log(data);
                                    if (data.trim() == '1') {

                                        $("#frmContact").replaceWith($(thankyoudiv));
                                    } else {
                                        $("#frmContact").replaceWith($(errordiv));
                                    }
                                }
                            });
                        });
                    });
                    } catch (err) {
                        $("#frmContact").replaceWith($(errordiv));
                    }
                    return false;
                }
            });

        });
    </script>
</body>

</html>