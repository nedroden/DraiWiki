<div id="form_area">
    <div class="section_header">
        {{ _localized('auth.user_login') }}
    </div>

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for msg in errors %}
                    <li>{{ msg }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <div class="section_content">
        <form action="{{ action }}" method="post">
            <p>{{ _localized('auth.please_enter_email') }}</p>
            <label 	for="email"{% if errors.email is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.email') }}
            </label>
            <input 	type="text"
                      name="email"
                      placeholder="{{ _localized('auth.placeholder_email') }}"
                      maxlength="{{ max_email_length }}" /><br />
            <label 	for="password"{% if errors.password is not empty %} class="contains_error"{% endif %}>
                {{ _localized('auth.password') }}
            </label>
            <input 	type="password"
                      name="password"
                      placeholder="{{ _localized('auth.placeholder_password') }}"
                      maxlength="{{ max_password_length }}" /><br />
            <input type="submit" value="{{ _localized('auth.authenticate') }}" />
        </form>
    </div>
</div>