<div class="content_section">
    <div id="settings_page">
        {if $submitted}
            <div class="message_box{if not $errors eq ''} error{else} success{/if}">
                {if not $errors eq ''}
                    <ul>
                    {foreach $errors error}
                        <li>{$error}</li>
                    {/foreach}
                    </ul>
                {else}
                {$locale->read('management', 'settings_updated')}
                {/if}
            </div>
        {/if}

        <form action="{$action}" method="post">
            {$table}
            <input type="submit" value="{$locale->read('management', 'save')}" />
        </form>
    </div>
</div>