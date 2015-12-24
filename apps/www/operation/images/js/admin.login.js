/**
 * Created by Loin on 14-7-29.
 */
var Login = function() {
    return {
        init: function() {
            $('.login-form').validate({
                errorElement: 'label',
                errorClass: 'help-inline',
                focusInvalid: false,
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    username: {
                        required: "帐号不能为空"
                    },
                    password: {
                        required: "密码不能为空"
                    }
                },
                highlight: function (element) {
                    $(element)
                        .closest('.control-group').addClass('error');
                },
                success: function (label) {
                    label.closest('.control-group').removeClass('error');
                    label.remove();
                },
                errorPlacement: function (error, element) {
                    error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
                }
            });
            $('.login-form input').on('keypress', function (e) {
                if (e.which == 13) {
                    if ($('.login-form').validate().form()) {
                        return true;
                    }
                    return false;
                }
            });

            $('.set-form').validate({
                errorElement: 'label',
                errorClass: 'help-inline',
                focusInvalid: false,
                ignore: '',
                rules: {
                    password: {
                        minlength: 6,
                        required: true
                    },
                    rpassword: {
                        equalTo: '[name="password"]'
                    }
                },
                messages: {
                    password: {
                        minlength: "新密码长度不能少于6个字符",
                        required: "新密码不能为空"
                    },
                    rpassword: {
                        equalTo: "两次输入密码不一致"
                    }
                },
                highlight: function (element) {
                    $(element)
                        .closest('.control-group').addClass('error');
                },
                success: function (label) {
                    label.closest('.control-group').removeClass('error');
                    label.remove();
                },
                errorPlacement: function (error, element) {
                    error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
                }
            });
            $('.set-form input').on('keypress', function (e) {
                if (e.which == 13) {
                    if ($('.set-form').validate().form()) {
                        return true;
                    }
                    return false;
                }
            });

            $('.change-form').validate({
                errorElement: 'span',
                errorClass: 'help-inline',
                focusInvalid: false,
                ignore: "",
                rules: {
                    opassword: {
                        required: true
                    },
                    password: {
                        minlength: 6,
                        required: true
                    },
                    rpassword: {
                        equalTo: '[name="password"]'
                    }
                },
                messages: {
                    opassword: {
                        required: "请输入原始密码"
                    },
                    password: {
                        minlength: "新密码长度不能少于6个字符",
                        required: "新密码不能为空"
                    },
                    rpassword: {
                        equalTo: "两次输入密码不一致"
                    }
                },
                invalidHandler: function (event, validator) {

                },
                highlight: function (element) {
                    $(element)
                        .closest('.help-inline').removeClass('ok');
                    $(element)
                        .closest('.control-group').removeClass('success').addClass('error');
                },
                unhighlight: function (element) {
                    $(element)
                        .closest('.control-group').removeClass('error');
                },
                success: function (label) {
                    label
                        .addClass('valid').addClass('help-inline ok')
                        .closest('.control-group').removeClass('error').addClass('success');
                }
            });
            $('.change-form input').on('keypress', function (e) {
                if (e.which == 13) {
                    if ($('.change-form').validate().form()) {
                        return true;
                    }
                    return false;
                }
            });
        }
    }
}();