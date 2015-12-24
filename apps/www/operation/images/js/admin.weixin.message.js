/**
 * Created by Loin on 14-8-7.
 */
$(function() {

    $('.edit-form').validate({
        errorElement: 'label',
        errorClass: 'help-inline',
        focusInvalid: false,
        rules: {
            title: {
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

    var validateData = function() {
        var data = '';
        var type = $('[name="type"]', '.edit-form').val();
        if(type == 'text') {
            data = $.trim($('[data-type="text"] textarea', '.edit-form').val());
            if(data !== '') {
                $('[data-type="text"] .control-group', '.edit-form').removeClass('error');
            } else {
                $('[data-type="text"] .control-group', '.edit-form').addClass('error');
            }
        } else {
            var items = $('[data-type="news"] .table tbody tr', '.edit-form');
            var list = [];
            items.each(function() {
                var self = $(this);
                var item = {};
                item['title'] = self.find('td:eq(1) a').text();
                item['desc'] = self.find('td:eq(1) span').text();
                item['pic'] = self.find('td:eq(2) span').text();
                item['url'] = self.find('td:eq(1) a').attr('href');
                list.push(item);
            });
            if(list.length > 0) {
                data = JSON.stringify(list);
                $('[data-type="news"] .control-group', '.edit-form').removeClass('error');
            } else {
                $('[data-type="news"] .control-group', '.edit-form').addClass('error');
            }
        }
        if(data) {
            $('[name="data"]', '.edit-form').text(data).val(data);
            return true;
        } else {
            return false;
        }
    };

    $('.edit-form input').on('keypress', function (e) {
        if (e.which == 13) {
            if ($('.edit-form').validate().form()) {
                return validateData();
            }
            return false;
        }
    });

    $('.edit-form').on('submit', function() {
        return validateData();
    });

    var uploader = new plupload.Uploader({
        runtimes: 'flash,html4',
        browse_button: '_j_add_news_pic',
        flash_swf_url: '/swf/Moxie.swf',
        container: '_j_add_news',
        filters: [{
            title: 'Image files',
            extensions: 'jpg,jpeg,png,gif'
        }],
        url: '/media/weixin.php/uploadPic',
        multipart: 'form-data'
    });
    uploader.init();
    uploader.bind('FilesAdded', function(up, files) {
        uploader.disableBrowse(true);
        uploader.start();
    });
    uploader.bind('UploadProgress', function(up, file) {
        $('#_j_add_news_pic')
            .closest('.fileupload')
            .find('.fileupload-preview')
            .text('正在上传...');
    });
    uploader.bind('FileUploaded', function(up, file, response) {
        var data = $.parseJSON(response.response);
        if(data.url) {
            $('#_j_add_news_pic')
                .closest('.fileupload')
                .removeClass('fileupload-new')
                .addClass('fileupload-exists')
                .find('.fileupload-preview')
                .html('已上传&nbsp;<a href="' + data.url + '" target="_blank">查看</a>');
            $('[name="news_pic"]', '#_j_add_news').val(data.id);
            $('.news-form', '#_j_add_news').validate().element('[name="news_pic"]');
        } else {
            $('#_j_add_news_pic')
                .closest('.fileupload')
                .find('.fileupload-preview')
                .text('上传失败');
        }
        uploader.disableBrowse(false);
    });
    //初始化添加图文表单
    var resetNewsForm = function() {
        $('#_j_add_news')
            .find('.control-group')
            .removeClass('error')
            .end()
            .find('.fileupload')
            .removeClass('fileupload-exists')
            .removeClass('fileupload-new')
            .find('.fileupload-preview')
            .html('');
    };
    //切换消息类型
    $('[name="type"]', '.edit-form').on('change', function() {
        var type = $(this).val();
        $('.edit-form')
            .find('> .row-fluid[data-type="' + type + '"]')
            .show()
            .siblings('.row-fluid')
            .hide();
    });
    $('.edit-form')
        .delegate('.add-news', 'click', function(e) { //添加图文项
            e.preventDefault();
            resetNewsForm();

            $('#_j_add_news').find('.fileupload').addClass('fileupload-new');
            $('[name="news_no"]', '#_j_add_news').val(-1);
            $('[name="news_title"]', '#_j_add_news').val('');
            $('[name="news_desc"]', '#_j_add_news').text('').val('');
            $('[name="news_pic"]', '#_j_add_news').val('');
            $('[name="news_url"]', '#_j_add_news').val('');

            $('#_j_add_news').modal();
        })
        .delegate('.table tbody .btn.mini', 'click', function(e) {
            e.preventDefault();
            var self = $(this);
            var item = self.closest('tr');
            if(self.index() == 0) {
                var title = item.find('td:eq(1) a').text();
                var desc = item.find('td:eq(1) span').text();
                var pic = item.find('td:eq(2) span').text();
                var pic_url = item.find('td:eq(2) a').attr('href');
                var url = item.find('td:eq(1) a').attr('href');

                resetNewsForm();
                $('#_j_add_news')
                    .find('.fileupload').addClass('fileupload-exists')
                    .find('.fileupload-preview').html('已上传&nbsp;<a href="'+pic_url+'" target="_blank">查看</a>');

                $('[name="news_no"]', '#_j_add_news').val(item.index());
                $('[name="news_title"]', '#_j_add_news').val(title);
                $('[name="news_desc"]', '#_j_add_news').text(desc).val(desc);
                $('[name="news_pic"]', '#_j_add_news').val(pic);
                $('[name="news_url"]', '#_j_add_news').val(url);

                $('#_j_add_news').modal();
            } else {
                if(confirm('确定删除该项？')) {
                    item.fadeOut(500, function() {
                        $('.table tbody tr', '.edit-form').each(function() {
                            $(this).find('td:eq(0)').text($(this).index() + 1);
                        });
                        $('.add-news', '.edit-form').show();
                    });
                }
            }
        });
    $('#_j_add_news')
        .delegate('[data-key="confirm"]', 'click', function() {
            if(!$('.news-form', '#_j_add_news').validate().form()) {
                return false;
            }
            var index = parseInt($('[name="news_no"]', '#_j_add_news').val());
            var title = $('[name="news_title"]', '#_j_add_news').val();
            var desc = $('[name="news_desc"]', '#_j_add_news').val();
            var pic = $('[name="news_pic"]', '#_j_add_news').val();
            var url = $('[name="news_url"]', '#_j_add_news').val();
            var pic_url = $('.fileupload-preview a', '#_j_add_news').attr('href');
            if(index < 0) {
                var itemSize = $('.table tbody tr', '.edit-form').size();
                if(itemSize < 10) {
                    itemSize ++;
                    var item = '<tr>' +
                        '<td>'+itemSize+'</td>' +
                        '<td><a href="'+url+'" target="_blank">'+title+'</a><span class="hide">'+desc+'</span></td>' +
                        '<td><a href="'+pic_url+'" target="_blank">查看</a><span class="hide">'+pic+'</span></td>' +
                        '<td><a href="#" class="btn mini"><i class="icon-edit"></i></a>&nbsp;<a href="#" class="btn mini"><i class="icon-trash"></i></a></td>' +
                        '</tr>';
                    $('.table tbody', '.edit-form')
                        .append(item);
                }
                if(itemSize == 10) {
                    $('.add-news', '.edit-form').hide();
                }
                $('.table', '.edit-form').closest('.control-group').removeClass('error');
            } else {
                var item = '<tr>' +
                    '<td>'+(index + 1)+'</td>' +
                    '<td><a href="'+url+'" target="_blank">'+title+'</a><span class="hide">'+desc+'</span></td>' +
                    '<td><a href="'+pic_url+'" target="_blank">查看</a><span class="hide">'+pic+'</span></td>' +
                    '<td><a href="#" class="btn mini"><i class="icon-edit"></i></a>&nbsp;<a href="#" class="btn mini"><i class="icon-trash"></i></a></td>' +
                    '</tr>';
                $('.table tbody tr', '.edit-form').eq(index).replaceWith(item);
            }

            $('#_j_add_news').modal('hide');
        });

    $('.news-form', '#_j_add_news').validate({
        errorElement: 'label',
        errorClass: 'help-inline',
        focusInvalid: false,
        ignore: '',
        rules: {
            news_title: {
                required: true
            },
            news_desc: {
                required: true
            },
            news_pic: {
                required: true
            },
            news_url: {
                required: true,
                url: true
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

    $('input', '#_j_add_news').on('keypress', function (e) {
        if (e.which == 13) {
            if ($('.news-form', '#_j_add_news').validate().form()) {
                $('[data-key="confirm"]', '#_j_add_news').trigger('click');
            }
            return false;
        }
    });
});