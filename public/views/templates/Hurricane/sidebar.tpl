<div id="sidebar" class="col20">
<h1>{$locale->read('main', 'panel')}</h1>
    {foreach $items item}
        <div class="sidebar_header">{$item['label']}</div>
        <ul>
        {foreach $item['items'] subitem}
            <li><a href="{$subitem['href']}" target="_self">{$subitem['label']}</a></li>
        {/foreach}
        </ul>
    {/foreach}
</div>