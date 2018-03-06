<div class="content_section">
    <div id="settings_page">
        {% if submitted %}
            <div class="message_box{% if errors is not empty %} error{% else %} success{% endif %}">
                {% if errors is not empty %}
                    <ul>
                    {% for error in errors %}
                        <li>{{ error }}</li>
                    {% endfor %}
                    </ul>
                {% else %}
                    {{ _localized('management.settings_updated') }}
                {% endif %}
            </div>
        {% endif %}

        <form action="{{ action }}" method="post">
            {{ table }}
            <input type="submit" value="{{ _localized('management.save') }}" />
        </form>
    </div>
</div>