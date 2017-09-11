<div class="col60">
    {if $historical_version}
        <div class="message_box notice">
            {$locale->read('article', 'historical_version')}
        </div>
    {/if}
    <h1 class="article_title">{$title}</h1>
    <div id="article_body">
        {$body_safe}
    </div>
    <div id="last_updated_by">{$last_updated_by}</div>
</div>