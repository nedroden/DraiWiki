<div id="sidebar" class="col20">
    {foreach $items item}
        <div class="sidebar_header">{$item['label']}</div>
        <ul>
        {foreach $item['items'] subitem}
            <li><a{if not $subitem['request_confirm'] == ''} onclick="requestConfirm('{$subitem['href']}');" href="javascript:void(0);"{else} href="{$subitem['href']}"{/if} target="_self"{if not $subitem['id'] eq ''} id="{$subitem['id']}"{/if}>{$subitem['label']}</a></li>
        {/foreach}
        </ul>
    {/foreach}
</div>