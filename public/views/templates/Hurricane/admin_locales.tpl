<div class="content_section">
    <div class="message_box info">
        {{ _localized('management.upload_before_install') }}
    </div>

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
            {% for error in errors %}
                <li>{{ _localized('management.' ~ error) }}</li>
            {% endfor %}
            </ul>
        </div>
    {% endif %}

    {% for link in uninstalled_links %}
        <div class="message_box notice">
            {{ link }}
        </div>
    {% endfor %}

    <h1>{{ _localized('management.installed_locales') }}</h1>
    {{ installed_locales }}
</div>