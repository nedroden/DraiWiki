<div class="message_box" style="display: none"></div>

<div class="action_bar">
    <ul>
        {% for item in items %}
            <li><i class="fa {{ item.icon }}"></i> <a href="{{ item.href }}" onclick="{{ item.action }}">{{ item.label }}</a></li>
        {% endfor %}
    </ul>
</div>