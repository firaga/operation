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
    $(function() {
        var resetNestable = function() {

            $('#_j_menu_nestable').load('/menu/getTree', '', function() {
                $('#_j_menu_nestable').nestable();
            });
        };
        resetNestable();
    });
</script>