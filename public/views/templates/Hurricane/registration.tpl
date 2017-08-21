<div id="form_area">
    <div class="section_header">
        {$locale->read('auth', 'user_registration')}
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
            <p>{$locale->read('auth', 'this_is_the_registration_page')}</p>
            <label 	for="username"{if not $errors['username'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'username')}
            </label>
            <input 	type="text"
                      name="username"
                      placeholder="{$locale->read('auth', 'placeholder_username')}"
                      maxlength="{$max_username_length}"/><br />
            <label 	for="password"{if not $errors['password'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'password')}
            </label>
            <input 	type="password"
                      name="password"
                      placeholder="{$locale->read('auth', 'placeholder_password')}"
                      maxlength="{$max_password_length}"/><br />
            <label 	for="confirm_password"{if not $errors['confirm_password'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'confirm_password')}
            </label>
            <input 	type="password"
                      name="confirm_password"
                      maxlength="{$max_password_length}"/><br />
            <label 	for="email"{if not $errors['email'] eq '' or not $errors['email_address'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'email')}
            </label>
            <input 	type="text"
                      name="email"
                      placeholder="{$locale->read('auth', 'placeholder_email')}"
                      maxlength="{$max_email_length}"/><br />
            <hr class="upp_bot_mar" />
            <label 	for="first_name"{if not $errors['first_name'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'first_name')}
            </label>
            <input 	type="text"
                      name="first_name"
                      placeholder="{$locale->read('auth', 'placeholder_first_name')}"
                      maxlength="{$max_first_name_length}"/><br />
            <label 	for="last_name"{if not $errors['last_name'] eq ''} class="contains_error"{/if}>
                {$locale->read('auth', 'last_name')}
            </label>
            <input 	type="text"
                      name="last_name"
                      placeholder="{$locale->read('auth', 'placeholder_last_name')}"
                      maxlength="{$max_last_name_length}"/><br />
            <hr class="upp_bot_mar" />
            <div id="agreement">
                {$agreement}
            </div>
            <div id="accept_agreement">
                <input type="checkbox" name="agreement_accept" /> {$locale->read('auth', 'agreement_i_accept')}
            </div>
            <input type="submit" value="{$locale->read('auth', 'create')}" />
        </form>
    </div>
</div>