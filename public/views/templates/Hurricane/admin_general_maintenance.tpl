<div class="content_section">
    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% elseif success %}
        <div class="message_box success">
            <p>{{ _localized('management.task_executed') }}</p>
        </div>
    {% endif %}

    {% for action in actions %}
        <div class="maintenance_action maintenance_color_{{ action.class }}">
            <div class="small_padding">
                <h1>{{ action.title }}</h1>
                <p>{{ action.description }}</p>
            </div>
            <div class="form_submit_area">
                <a href="javascript:requestConfirm('{{ action.href }}')">{{ _localized('management.execute_task') }}</a>
            </div>
        </div>
    {% endfor %}
</div>