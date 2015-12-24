{extends file="common/modal.tpl"}
{block name="container"}
    <div class="form-horizontal" data-id="{$info.id}">
        <div class="control-group">
            <label class="control-label">标题</label>
            <div class="controls">
                <input type="text" name="text" value="{$info.text}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">归属关系</label>
            <div class="controls">
                <select name="parent">
                    {$options}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">链接地址</label>
            <div class="controls">
                <input type="text" name="url" value="{$info.url}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">是否隐藏左侧导航</label>
            <div class="controls">
                <select class="large m-wrap" name="mode">
                    <option value="0" {if $info.full_screen == 0} selected{/if}>不隐藏（默认）</option>
                    <option value="1" {if $info.full_screen == 1} selected{/if}>隐藏</option>
                </select>
            </div>
        </div>
    </div>
{/block}