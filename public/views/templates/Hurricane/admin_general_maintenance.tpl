<div class="content_section">
    {% for action in actions %}
        <div class="maintenance_action maintenance_color_{{ action.class }}">
            <a href="{$action['href']}"><h1>{{ action.title }}</h1></a>
            <p>{{ action.description }}</p>
        </div>
    {% endfor %}
</div>