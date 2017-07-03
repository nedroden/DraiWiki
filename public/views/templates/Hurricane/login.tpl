<div id="auth_area">
    <div class="section_header">
        {$locale->read('auth', 'user_login')}
    </div>
    <div class="section_content">
        <form action="action" method="post">
            <p>{$locale->read('auth', 'please_enter_email')}</p>
            <label 	for="email">
                {$locale->read('auth', 'email')}
            </label>
            <input 	type="text"
                      name="email"
                      placeholder="{$locale->read('auth', 'placeholder_email')}"
                      maxlength="{$max_email_length}" /><br />
            <label 	for="password">
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