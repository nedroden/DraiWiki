<div class="content_section">
    {% for action in actions %}
        <div class="maintenance_action maintenance_color_{{ action.class }}">
            <div class="small_padding">
                <h1>{{ action.title }}</h1>
                <p>{{ action.description }}</p>
            </div>
            <div class="form_submit_area">
                <a href="{{ action.href }}">{{ _localized('management.execute_task') }}</a>
            </div>
        </div>
    {% endfor %}
</div>