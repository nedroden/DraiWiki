<div id="account_section">

    {if not $errors eq ''}
        <div class="message_box error">
            <ul>
                {foreach $errors msg}
                    <li>{$msg}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    {if $success}
        <div class="message_box success">
            {$locale->read('auth', 'settings_have_been_saved')}
        </div>
    {/if}

    <div id="settings_left" class="col33">
        <h1 id="account_name">{$user->getUsername()}</h1>
        <ul id="account_info">
            <li>{$locale->read('auth', 'registration_date')}: {$user->getRegistrationDate()}</li>
            <li>{$locale->read('auth', 'birthdate')}: {$user->getBirthDate()}</li>
        </ul>
    </div>
    <div class="col66">
        <form action="{$action}" method="post">
            {$table}

            <input type="submit" value="{$locale->read('auth', 'save')}" />
        </form>
    </div>
    <br class="clear" />
</div>