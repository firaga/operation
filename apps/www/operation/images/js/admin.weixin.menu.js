/**
 * Created by Loin on 14-8-27.
 */
$(function() {

    //切换菜单类型
    $('[name="type"]', '.edit-form').on('change', function() {
        changeValueRule();
        var type = $(this).val();
        var element = $('[name="value"]', '.edit-form');
        if('service' == type) {
            element.closest('.control-group').hide();
        } else {
            element.closest('.control-group').show();
        }
    });

    $('.edit-form').validate({
        errorElement: 'label',
        errorClass: 'help-inline',
        focusInvalid: false,
        rules: {
            title: {
                required: true
            },
            value: {
                required: true
            }
        },
        highlight: function (element) {
            $(element)
                .closest('.control-group').addClass('error');
        },
        success: function (label, element) {
            $(element)
                .closest('.control-group').removeClass('error');
        },
        errorPlacement: function (error, element) {

        }
    });

    var changeValueRule = function() {
        var type = $('[name="type"]', '.edit-form').val();
        var element = $('[name="value"]', '.edit-form');
        if('view' == type) {
            element.rules('add', {
                url: true
            });
        } else {
            element.rules('remove', 'url');
        }
    };
    changeValueRule();
});