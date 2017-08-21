<div class="col60">
    <h1 class="article_title">{$title}</h1>
    <hr class="bottom_top_margin" />
    <div class="image">
        <img src="{$url}" alt="*" />
    </div>
    <hr />
    <h3>{$locale->read('tools', 'description')}</h3>
    {$description}

    <h3>{$locale->read('tools', 'info')}</h3>
    {$table}
</div>