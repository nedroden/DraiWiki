                <br class="clear" />
            </div>

            <div id="copyright">
                <div class="col33">{{ copyright | raw }}{% if locale_copyright != 'none' %}<br />{{ locale_copyright | raw }}{% endif %}</div>
                <div class="col33 align_center"><a id="to_top" href="#wrapper" title="{{ _localized('main.to_top') }}"><i id="to_top_arrow" class="fa fa-arrow-up"></i></a></div>
                <div class="col33 align_right">
                    <form action="{{ url }}/index.php/locale" method="post">
                        <label for="locale_switcher">{{ _localized('main.locale') }}:</label>
                        <select name="code" id="locale_switcher">
                        {% for continent in locale_continents %}
                            <optgroup label="{{ continent.label }}">
                            {% for lang in continent.locales %}
                                <option value="{{ lang.code }}"{% if lang.selected is not empty %} selected{% endif %}>{{ lang.native }}</option>
                            {% endfor %}
                            </optgroup>
                        {% endfor %}
                        </select>
                    </form>
                </div>
                <br class="clear" />
            </div>
        </div>
        {% if debug_body is not empty %}
            {{ debug_body }}
        {% endif %}
    </body>
</html>
