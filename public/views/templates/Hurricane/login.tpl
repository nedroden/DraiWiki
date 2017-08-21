<div id="form_area">
    <div class="section_header">
        {$locale->read('auth', 'user_login')}
    </div>

    {if not $errors eq ''}
        <div class="message_box error">
            <ul>
                {foreach $errors msg}
                    <li>{$msg}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <div class="section_content">
        <form action="{$action}" method="post">
            <p>{$locale->read('auth', 'please_enter_email')}</p>
            <label 	for="email"{if not $errors['email'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'email')}
            </label>
            <input 	type="text"
                      name="email"
                      placeholder="{$locale->read('auth', 'placeholder_email')}"
                      maxlength="{$max_email_length}" /><br />
            <label 	for="password"{if not $errors['password'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'password')}
            </label>
            <input 	type="password"
                      name="password"
                      placeholder="{$locale->read('auth', 'placeholder_password')}"
                      maxlength="{$max_password_length}" /><br />
            <input type="submit" value="{$locale->read('auth', 'authenticate')}" />
        </form>
    </div>
</div>