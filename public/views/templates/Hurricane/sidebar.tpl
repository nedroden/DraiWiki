<div id="sidebar" class="col20">
    {% for item in items %}
        <div class="sidebar_header">{{ item.label }}</div>
        <ul>
        {% for subitem in item.items %}
            <li><a{% if subitem.request_confirm is not empty %} onclick="requestConfirm('{{ subitem.href }}');" href="javascript:void(0);"{% else %} href="{{ subitem.href }}" {% endif %} target="_self"{% if subitem.id is not empty %} id="{{ subitem.id }}"{% endif %}>{{ subitem.label }}</a></li>
        {% endfor %}
        </ul>
    {% endfor %}
</div>