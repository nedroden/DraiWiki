<div class="content_section">
    <div class="message_box info">
        {$locale->read('management', 'upload_before_install')}
    </div>

    {if not $errors eq ''}
        <div class="message_box error">
            <ul>
            {foreach $errors error}
                <li>{$locale->read('management', $error)}</li>
            {/foreach}
            </ul>
        </div>
    {/if}

    {foreach $uninstalled_links link}
        <div class="message_box notice">
            {$link}
        </div>
    {/foreach}

    <h1>{$locale->read('management', 'installed_locales')}</h1>
    {$installed_locales}
</div>