<div class="form_table">
    {% for item in rows %}
        {% if item.is_header is not empty %}
            <h1>{{ item.label }}</h1>
        {% else %}
            <div>
                <input type="hidden" name="form_submitted" value="1"/>
                <label for="{{ item.name }}">
                    {{ item.label }}
                </label><br />
                <span>{{ item.description }}</span><br />
                {% if item.input_type == 'text' %}
                    <input type="text" name="{{ item.name }}"{% if item.value is not empty %} value="{{ item.value }}"{% endif %} />
                {% elseif item.input_type == 'checkbox' %}
                    <p><input type="checkbox" name="{{ item.name }}"{% if item.value == 1 %} checked{% endif %} />{% if item.input_description is not empty %} {{ item.input_description }}{% endif %}</p>
                {% elseif item.input_type == 'select' %}
                    <select name="{{ item.name }}">
                    {% for option in item.options %}
                        <option value="{{ option.value }}"{% if option.selected is not empty %} selected{% endif %}>{{ option.label }}</option>
                    {% endfor %}
                    </select>
                    <br class="clear" />
                {% endif %}
            </div>
        {% endif %}
    {% endfor %}
</div>