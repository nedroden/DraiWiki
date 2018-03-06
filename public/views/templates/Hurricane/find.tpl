<div class="col60">

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <form action="{{ action }}" method="post">
        <input type="text" id="search" name="terms" placeholder="{{ _localized('find.enter_a_search_term') }}" />
    </form>

    {% if articles is not empty %}
        <div class="message_box">{{ _localized('find.searched_for') }} <strong>{{ unparsed_search_terms }}</strong>. {{ number_of_results }}</div>
        <div id="results">
            {% for article in articles %}
                <div class="search_result">
                    <h1><a href="{{ article.href }}" target="_self">{{ article.title }}</a></h1>
                    {{ article.body_shortened }}
                </div>
            {% endfor %}
        </div>

        {% if show_load_more %}
            <a id="more_results" href="javascript:void(0);" onclick="loadMoreSearchResults('{{ max_results }}', '{{ search_terms }}');">{{ _localized('find.load_more_results') }}</a>
        {% endif %}
    {% elseif has_loaded_articles %}
        <div class="message_box notice">
            {{ _localized('find.no_results_were_found') }}
        </div>
    {% endif %}
</div>