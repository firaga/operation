<div class="modal-header">
    {if !$hide_close}
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    {/if}
    <h3>{if $title}{$title}{else}提示{/if}</h3>
</div>
<div class="modal-body">
    <div class="row-fluid">
        {block name="container"}{/block}
    </div>
</div>
<div class="modal-footer">
    <span class="help-inline"></span>
    {if !$hide_cancel}
        <button type="button" data-dismiss="modal" class="btn">{if $cancel_text}{$cancel_text}{else}取消{/if}</button>
    {/if}
    {block name="buttons"}
        <button type="button" class="btn blue" data-key="confirm">确定</button>
    {/block}
</div>

