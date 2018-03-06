{% if type == 'info_table' %}
    <div class="info_table">
        {% for cell in rows %}
            <div>
                <span>{{ cell.0 }}</span>
                <span>{{ cell.1 }}</span>
            </div>
        {% endfor %}
        <br class="clear" />
    </div>
    {% else %}
    <div class="count float_left"></div><div class="pagination float_right"></div><br class="clear" />
    <table id="{{ id }}" class="{{ type }}">
        <thead>
            {% for column in columns %}
                <th>{{ column }}</th>
            {% endfor %}
        </thead>
        <tbody>
            {% for row in rows %}
                <tr>
                    {% for cell in row %}
                        <td>{{ cell }}</td>
                    {% endfor %}
                </tr>
            {% endfor %}
        </tbody>
    </table>
    <div class="count float_left"></div><div class="pagination float_right"></div><br class="clear" />
{% endif %}