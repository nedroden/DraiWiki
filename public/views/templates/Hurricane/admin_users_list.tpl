            <div class="content_section">
                {% if errors is not empty %}
                    <div class="message_box error">
                        <ul>
                            {% for error in errors %}
                                <li>{{ error }}</li>
                            {% endfor %}
                        </ul>
                    </div>
                {% endif %}

                {{ table }}
            </div>

            <script type="text/javascript">activateTable('/management/users/ajax/getlist');</script>