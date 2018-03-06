<div id="account_section">

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    {% if success %}
        <div class="message_box success">
            {{ _localized('auth.settings_have_been_saved') }}
        </div>
    {% endif %}

    <div id="settings_left" class="col33">
        <h1 id="account_name">{{ user.getUsername() }}</h1>

        <!-- Avatar placeholder //-->
        <img src="http://www.infragistics.com/media/8948/anonymous_200.gif" width="100px" height="100px" />
        <ul id="account_info">
            <li>{{ _localized('auth.registration_date') }}: {{ user.getRegistrationDate() }}</li>
            <li>{{ _localized('auth.birthdate') }}: {{ user.getBirthDate() }}</li>
        </ul>
    </div>
    <div class="col66">
        <form action="{{ action }}" method="post">
            {{ table }}

            <input type="submit" value="{{ _localized('auth.save') }}" />
        </form>
    </div>
    <br class="clear" />
</div>