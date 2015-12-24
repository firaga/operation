<div class="box">
    <div class="box-header">
        <a href="#" class="btn green" id="_j_menu_append">新增导航</a>
    </div>
    <div class="box-body" id="_j_menu_nestable">

    </div>
</div>
<div class="modal hide fade" tabindex="-1" id="_j_menu_edit"></div>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/metronic/bootstrap-modal.js"></script>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/metronic/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/jquery.nestable.js"></script>
<script type="text/javascript">
    $(function () {
        var resetNestable = function () {

            $('#_j_menu_nestable').load('/menu/getTree', '', function () {
                $('#_j_menu_nestable').nestable();
            });
        };
        resetNestable();
        $('#_j_menu_append').on('click', function (e) {
            e.preventDefault();
            console.log('click')
            $('#_j_menu_edit').load('/menu/edit', '', function () {
                $('#_j_menu_edit').modal();
            });
        });
        $('#_j_menu_edit')
                .delegate('[data-key="confirm"]', 'click', function () {
                    var self = $(this),
                            formObj = $('#_j_menu_edit .form-horizontal'),
                            id = formObj.data('id'),
                            text = $.trim(formObj.find('[name="text"]').val()),
                            parentId = formObj.find('[name="parent"]').val(),
                            url = $.trim(formObj.find('[name="url"]').val()),
                            mode = $.trim(formObj.find('[name="mode"]').val());
                    if (self.hasClass('disabled')) {
                        return false;
                    }
                    self.addClass('disabled');
                    $.post('/menu/edit', {
                        id: id,
                        text: text,
                        parentid: parentId,
                        url: url,
                        mode: mode
                    }, function (data) {
                        if (data > 0) {
                            $('#_j_menu_edit').modal('hide');
                            resetNestable();
                        } else {
                            $.alerts.error('操作失败，请稍候再试');
                        }
                        self.removeClass('disabled');
                    });
                });
        $('#_j_menu_nestable')
                .delegate('.dd-add', 'click', function (e) {
                    e.preventDefault();
                    var item = $(this).closest('li');
                    var parentId = item.data('id');
//                    $('body').modalmanager('loading');
                    $('#_j_menu_edit').load('/menu/edit?parentid=' + parentId, '', function () {
                        $('#_j_menu_edit').modal();
                    });
                })
                .delegate('.dd-edit', 'click', function (e) {
                    e.preventDefault();
                    var item = $(this).closest('li');
                    var id = item.data('id');
//                    $('body').modalmanager('loading');
                    $('#_j_menu_edit').load('/menu/edit?id=' + id, '', function () {
                        $('#_j_menu_edit').modal();
                    });
                })
                .delegate('.dd-privacy', 'click', function (e) {
                    e.preventDefault();
                    var item = $(this).closest('li');
                    var id = item.data('id');
//                    $('body').modalmanager('loading');
                    $('#_j_menu_edit').load('menu/privacy/?id=' + id, '', function () {
                        $('#_j_menu_edit').modal();
                    });
                })
                .delegate('.dd-delete', 'click', function (e) {
                    e.preventDefault();
                    var self = $(this),
                            item = self.closest('li'),
                            id = item.data('id');
                    if (self.hasClass('disabled')) {
                        return false;
                    }
                    if (!confirm('确定删除该项？')) {
                        return false;
                    }
                    self.addClass('disabled');
                    $.post('/menu/del', {
                        id: id
                    }, function (data) {
                        if (data > 0) {
                            $.alerts.success('删除成功');
                            resetNestable();
                        } else {
                            $.alerts.error('删除失败，请稍候再试');
                        }
                        self.removeClass('disabled');
                    });
                });
    });
</script>