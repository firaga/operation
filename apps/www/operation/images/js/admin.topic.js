/**
 * Created by maran on 15/2/27.
 */
$(function() {
    //判断是修改还是新增
    var topic_id = $("#primary_id").data("topic_id");
    if(topic_id){
        var pic_heng_url = $("#pic_heng_url").val();
        var pic_shu_url = $("#pic_shu_url").val();
        $('#upload_pic')
            .find('.fileupload').addClass('fileupload-exists')
            .find('.fileupload-preview').eq(0).html('已上传&nbsp;<a href="'+pic_heng_url+'" target="_blank">查看</a>');
        $('#upload_pic').find('.fileupload-preview').eq(1).html('已上传&nbsp;<a href="'+pic_shu_url+'" target="_blank">查看</a>');
    }else{
        $('#upload_pic').find('.fileupload').addClass('fileupload-new');
    }

    //横图上传
    var uploader = new plupload.Uploader({
        runtimes: 'flash,html4',
        browse_button: '_j_add_heng_pic',
        flash_swf_url: '/swf/Moxie.swf',
        filters: [{
            title: 'Image files',
            extensions: 'jpg,jpeg,png,gif'
        }],
        url: '/media/topic.php/uploadPic',
        multipart: 'form-data'
    });
    uploader.init();
    uploader.bind('FilesAdded', function(up, files) {
        uploader.disableBrowse(true);
        uploader.start();
    });
    uploader.bind('UploadProgress', function(up, file) {
        $('#_j_add_heng_pic')
            .closest('.fileupload')
            .find('.fileupload-preview')
            .text('正在上传...');
    });
    uploader.bind('FileUploaded', function(up, file, response) {
        var data = $.parseJSON(response.response);
        if(data.url) {
            $('#_j_add_heng_pic')
                .closest('.fileupload')
                .removeClass('fileupload-new')
                .addClass('fileupload-exists')
                .find('.fileupload-preview')
                .html('已上传&nbsp;<a href="' + data.url + '" target="_blank">查看</a>');
            $('[name="pic_heng"]', '#upload_pic').val(data.id);
            $('.news-form', '#upload_pic').validate().element('[name="pic_heng"]');
        } else {
            $('#_j_add_heng_pic')
                .closest('.fileupload')
                .find('.fileupload-preview')
                .text('上传失败');
        }
        uploader.disableBrowse(false);
    });

    //竖图上传
    var uploader_shu = new plupload.Uploader({
        runtimes: 'flash,html4',
        browse_button: '_j_add_shu_pic',
        flash_swf_url: '/swf/Moxie.swf',
        filters: [{
            title: 'Image files',
            extensions: 'jpg,jpeg,png,gif'
        }],
        url: '/media/topic.php/uploadPic',
        multipart: 'form-data'
    });
    uploader_shu.init();
    uploader_shu.bind('FilesAdded', function(up, files) {
        uploader_shu.disableBrowse(true);
        uploader_shu.start();
    });
    uploader_shu.bind('UploadProgress', function(up, file) {
        $('#_j_add_shu_pic')
            .closest('.fileupload')
            .find('.fileupload-preview')
            .text('正在上传...');
    });
    uploader_shu.bind('FileUploaded', function(up, file, response) {
        var data = $.parseJSON(response.response);
        if(data.url) {
            $('#_j_add_shu_pic')
                .closest('.fileupload')
                .removeClass('fileupload-new')
                .addClass('fileupload-exists')
                .find('.fileupload-preview')
                .html('已上传&nbsp;<a href="' + data.url + '" target="_blank">查看</a>');
            $('[name="pic_shu"]', '#upload_pic').val(data.id);
            $('.news-form', '#upload_pic').validate().element('[name="pic_shu"]');
        } else {
            $('#_j_add_shu_pic')
                .closest('.fileupload')
                .find('.fileupload-preview')
                .text('上传失败');
        }
        uploader_shu.disableBrowse(false);
    });

    //表单验证
    $('.edit-form').validate({
        errorElement: 'label',
        errorClass: 'help-inline',
        focusInvalid: false,
        ignore: '',
        rules: {
            title: {
                required: true
            },
            type: {
                required: true
            },
            pic_heng: {
                required: true
            },
            pic_shu: {
                required: true
            }
        },
        highlight: function (element) {
            $(element).closest('.control-group').addClass('error');
        },
        success: function (label, element) {
            $(element).closest('.control-group').removeClass('error');
        },
        errorPlacement: function (error, element) {

        }
    });

    //表单验证
    $('.place-edit-form').validate({
        errorElement: 'label',
        errorClass: 'help-inline',
        focusInvalid: false,
        ignore: '',
        rules: {
            pic_heng: {
                required: true
            },
            poi_ids: {
                required: true
            }
        },
        highlight: function (element) {
            $(element).closest('.control-group').addClass('error');
        },
        success: function (label, element) {
            $(element).closest('.control-group').removeClass('error');
        },
        errorPlacement: function (error, element) {

        }
    });

    $("#selected_base").change(function(){
        if($(this).val() == 1){
            $("#mdd_poi_ids").hide();
        }else if($(this).val() == 0){
            $("#mdd_poi_ids").show();
        }
    });
});