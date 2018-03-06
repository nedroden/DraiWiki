<div id="form_area">
    <div class="section_header">
        {{ _localized('auth.user_registration') }}
    </div>

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    
    <form action="{{ action }}" method="post">
        <div class="section_content">
            <p>{{ _localized('auth.this_is_the_registration_page') }}</p>
            <label 	for="username"{% if errors.username is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.username') }}
            </label>
            <input 	type="text"
                      name="username"
                      placeholder="{{ _localized('auth.placeholder_username') }}"
                      maxlength="{{ max_username_length }}"/><br />
            <label 	for="password"{% if errors.password is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.password') }}
            </label>
            <input 	type="password"
                      name="password"
                      placeholder="{{ _localized('auth.placeholder_password') }}"
                      maxlength="{{ max_password_length }}"/><br />
            <label 	for="confirm_password"{% if errors.confirm_password is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.confirm_password') }}
            </label>
            <input 	type="password"
                      name="confirm_password"
                      maxlength="{{ max_password_length }}"/><br />
            <label 	for="email"{% if errors.email is not empty or errors.email_address is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.email') }}
            </label>
            <input 	type="text"
                      name="email"
                      placeholder="{{ _localized('auth.placeholder_email') }}"
                      maxlength="{{ max_email_length }}"/><br />
            <hr class="upp_bot_mar" />
            <label 	for="first_name"{% if errors.first_name is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.first_name') }}
            </label>
            <input 	type="text"
                      name="first_name"
                      placeholder="{{ _localized('auth.placeholder_first_name') }}"
                      maxlength="{{ max_first_name_length }}"/><br />
            <label 	for="last_name"{% if errors.last_name is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.last_name') }}
            </label>
            <input 	type="text"
                      name="last_name"
                      placeholder="{{ _localized('auth.placeholder_last_name') }}"
                      maxlength="{{ max_last_name_length }}"/><br />
            <hr class="upp_bot_mar" />
            <div id="agreement">
                {{ agreement }}
            </div>
            <div id="accept_agreement">
                <input type="checkbox" name="agreement_accept" /> {{ _localized('auth.agreement_i_accept') }}
            </div>
        </div>
        <div class="form_submit_area">
            <input type="submit" value="{{ _localized('auth.create') }}" />
        </div>
    </form>
</div>