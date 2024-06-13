<script src="<?= BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>assets/js/jquery-3.6.0.min.js"></script>
<script src="<?= BASE_URL; ?>assets/js/slick.min.js"></script>
<script src="<?= BASE_URL; ?>assets/js/choices.min.js"></script>
<script src="<?= BASE_URL; ?>assets/js/app.js"></script>
<script src="<?= BASE_URL; ?>assets/js/jquery.validate.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?= BASE_URL; ?>assets/js/custom.js"></script>


<script>
    function googleTranslateElementInit2() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            autoDisplay: false
        }, 'google_translate_element2');
    }
    if (!window.gt_translate_script) {
        window.gt_translate_script = document.createElement('script');
        gt_translate_script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2';
        document.body.appendChild(gt_translate_script);
    }

    function GTranslateGetCurrentLang() {
        var keyValue = document['cookie'].match('(^|;) ?googtrans=([^;]*)(;|$)');
        return keyValue ? keyValue[2].split('/')[2] : null;
    }

    function GTranslateFireEvent(element, event) {
        try {
            if (document.createEventObject) {
                var evt = document.createEventObject();
                element.fireEvent('on' + event, evt)
            } else {
                var evt = document.createEvent('HTMLEvents');
                evt.initEvent(event, true, true);
                element.dispatchEvent(evt)
            }
        } catch (e) {}
    }

    function doGTranslate(lang_pair) {
        if (lang_pair.value) lang_pair = lang_pair.value;
        if (lang_pair == '') return;
        var lang = lang_pair.split('|')[1];
        if (GTranslateGetCurrentLang() == null && lang == lang_pair.split('|')[0]) return;
        if (typeof ga == 'function') {
            ga('send', 'event', 'GTranslate', lang, location.hostname + location.pathname + location.search);
        }
        var teCombo;
        var sel = document.getElementsByTagName('select');
        for (var i = 0; i < sel.length; i++)
            if (sel[i].className.indexOf('goog-te-combo') != -1) {
                teCombo = sel[i];
                break;
            } if (document.getElementById('google_translate_element2') == null || document.getElementById('google_translate_element2').innerHTML.length == 0 || teCombo.length == 0 || teCombo.innerHTML.length == 0) {
            setTimeout(function() {
                doGTranslate(lang_pair)
            }, 500)
        } else {
            teCombo.value = lang;
            GTranslateFireEvent(teCombo, 'change');
            GTranslateFireEvent(teCombo, 'change')
        }
    }

    (function gt_jquery_ready() {
        if (!window.jQuery || !jQuery.fn.click) return setTimeout(gt_jquery_ready, 20);
        jQuery(document).ready(function() {
            var allowed_languages = ["nl", "en", "fr", "es"];
            var accept_language = navigator.language.toLowerCase() || navigator.userLanguage.toLowerCase();
            switch (accept_language) {
                case 'zh-cn':
                    var preferred_language = 'zh-CN';
                    break;
                case 'zh':
                    var preferred_language = 'zh-CN';
                    break;
                case 'zh-tw':
                    var preferred_language = 'zh-TW';
                    break;
                case 'zh-hk':
                    var preferred_language = 'zh-TW';
                    break;
                case 'he':
                    var preferred_language = 'iw';
                    break;
                default:
                    var preferred_language = accept_language.substr(0, 2);
                    break;
            }
            if (preferred_language != 'en' && GTranslateGetCurrentLang() == null && document.cookie.match('gt_auto_switch') == null && allowed_languages.indexOf(preferred_language) >= 0) {
                doGTranslate('en|' + preferred_language);
                document.cookie = 'gt_auto_switch=1; expires=Thu, 05 Dec 2030 08:08:08 UTC; path=/;';
            }
        });
    })();

    $(document).ready(function() {

        <?php if (!empty($orderCount)) { ?>
            $("#id-cart-value").html(<?= $orderCount; ?>);
            $("#id-cart-value2").html(<?= $orderCount; ?>);
        <?php } else { ?>
            var retrievedObject = null;
            if (localStorage) {
                retrievedObject = localStorage.getItem('myArray');
            } else {
                alert("Error: This browser is still not supported; Please use google chrome!");
            }
            var parsedArray = null;
            if (retrievedObject) {
                parsedArray = JSON.parse(retrievedObject);
            }
            if (parsedArray == null) {
                $("#id-cart-value").html(0);
                $("#id-cart-value2").html(0);

            } else {
                $("#id-cart-value").html(parsedArray.length);
                $("#id-cart-value2").html(parsedArray.length);
            }
        <?php } ?>

    });

    $(document).ready(function() {
        //for send rwquest
        $('#send_reqst_btn').click(function() {

            let user_email = $('#send_request_email').val();
            let rqst_msg = $('#send_request_msg').val();

            if (user_email == '') {
                alert('please enter email address');
                return false;
            }
            if (rqst_msg == '') {
                alert('please enter your message');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/user.php",
                data: {
                    action: "new_rqst_email_sender",
                    user_email: user_email,
                    rqst_msg: rqst_msg
                },

                beforeSend: function() {
                    //console.log('ajax start');
                    $('#send_reqst_btn').text('Sending...');
                },
                success: function(response) {
                    console.log(response);
                    //JSON.parse(response);
                    if (response == 1) {
                        $('#send_reqst_btn').text('Your request has beeb send successfully').css('color', 'green');
                        setTimeout(function() {
                            location.reload();
                            //window.location.href='<?= BASE_URL; ?>';
                        }, 1000)
                        //alert('Your request has beeb send successfully');
                    }

                },
                error: function() {
                    console.log('ajax not running.');
                }
            });
        });

        $(".modal-close").click(function() {
            $(".product-discription-form").removeClass("form-active");
        });


        //for login
        $('#login_btn').click(function() {

            let username = $('#username').val();
            let password = $('#password').val();

            if (username == '') {
                alert('please enter username');
                return false;
            }
            if (password == '') {
                alert('please enter your password');
                return false;
            }

            $.ajax({
                type: "POST",
                url: "<?= BASE_URL ?>ajax/user.php",
                data: {
                    action: "user_login",
                    username: username,
                    password: password,
                    myData: localStorage.getItem('myArray')
                },

                beforeSend: function() {
                    //console.log('ajax start');
                    $('#login_btn').text('Sending...');
                },
                success: function(response) {
                    //alert(response);
                    console.log(response);
                    //debugger;
                    // //JSON.parse(response);
                    if (response == 'logged_in_seccuess') {
                        $('#login_btn').text('logged in successfully').css('color', 'green');
                        setTimeout(function() {
                            localStorage.clear()
                            location.reload();
                            //window.location.href='<?= BASE_URL; ?>';
                        }, 500)
                    } else if (response == 'password_error') {
                        $('#login_btn').text('Log In');
                        alert('Please enter valid password');
                        return false;
                    } else if (response == 'user_error') {
                        $('#login_btn').text('Log In');
                        alert('Please enter valid username');
                        return false;
                    }

                },

            });

        });


    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Only alphabetical characters");

    jQuery.validator.addMethod("alpha_numeric", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s]+$/i.test(value);
    }, "Only alphanumeric characters");



    jQuery.validator.addMethod("numberonly", function(value, element) {
        return this.optional(element) || /^[0-9+()#\s]+(\-[0-9()\s]+)*$/i.test(value);
    }, "Only numbers are allowed");


    $.validator.addMethod("tendigits", function(value, element) {
        return this.optional(element) || /^[0-9]{7,15}$/.test(value);
    }, "Please enter a valid number with 7 to 15 digits.");

    jQuery.validator.addMethod("validate_email", function(value, element) {
        if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter a valid Email.");

    jQuery("#news_form").validate({
        rules: {


            news_email: {
                required: true,
                email: true,
                validate_email: true
            }


        },
        messages: {

            news_email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                validate_email: "Please enter a valid email address"
            }

        },
        submitHandler: function(form) {
            $(form).find(":submit").attr("disabled", true).html("Submitting...");
            form.submit();
        }
    });

    jQuery("#contact_form").validate({
        rules: {
            theme: {
                required: true,
                lettersonly: true
            },

            email: {
                required: true,
                email: true,
                validate_email: true
            },
            tandc: {
                required: true
            },
            message: {
                required: true,
                alpha_numeric: true
            }

        },
        messages: {
            theme: {
                required: "Please enter your theme",
                lettersonly: "Invalid name. Only letters allowed"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                validate_email: "Please enter a valid email address"
            },
            tandc: {
                required: "Please checked T & C before submit",
            },

            message: {
                required: "Please enter your message",
                alpha_numeric: "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed."
            }


        },
        submitHandler: function(form) {
            if (grecaptcha.getResponse() == ''){
                    $( '#reCaptchaError' ).html( '<p class="captcha_error">Please verify you are human</p>' );
                } else{
                    $(form).find(":submit").attr("disabled", true).html("Submitting...");
                    form.submit();
                }
           
        }
    });

    jQuery("#request_form").validate({

        rules: {
            Person: {
                required: true,
                lettersonly: true
            },

            Email: {
                required: true,
                email: true,
                validate_email: true
            },
            Buyer_type: {
                required: true
            },
            organization_name: {
                required: true,
                alpha_numeric: true
            },
            Address: {
                alpha_numeric: true
            }, 
            message: {
                alpha_numeric: true
            }, 
            Phone: {
                numberonly: true,
                tendigits:true
            }, 

        },
        messages: {
            Person: {
                required: "Please enter your name.",
                lettersonly: "Invalid name. Only letters allowed"
            },
            Email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                validate_email: "Please enter a valid email address"
            },
            Buyer_type: {
                required: "Please select any buyer type",
            },

            organization_name: {
                required: "Please enter your organization name",
                alpha_numeric: "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed."
            },
            Address: {
                alpha_numeric: "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed."
            },
            message: {
                alpha_numeric: "URLs and special characters such as <,>,=,?,.$,#, etc. are not allowed."
            },
            Phone: {
                required: "Please enter valid contact number.",
            },


        },

        submitHandler: function(form) {
            $(form).find(":submit").attr("disabled", true).html("Submitting...");
            form.submit();
        }
    });
    $(document).ready(function() {
        $("#toggle").change(function() {

            // Check the checkbox state
            if ($(this).is(':checked')) {
                // Changing type attribute
                $("#password").attr("type", "text");

                // Change the Text
                $("#toggleText").text("Hide");
            } else {
                // Changing type attribute
                $("#password").attr("type", "password");

                // Change the Text
                $("#toggleText").text("Show");
            }

        });
    });
</script>