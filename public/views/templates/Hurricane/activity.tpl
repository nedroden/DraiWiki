<div class="col66">
    <h1>{{ _localized('main.activity') }}</h1>

    <div id="updates">
        {% for update in updates %}
            <div class="update">
                <span>{{ update.time }}</span>
                <span>{{ update.text }}</span>
            </div>
        {% endfor %}
    </div>

    {% if show_load_more %}
        <a id="more_results" href="javascript:void(0);" onclick="loadMoreUpdates('{{ results_per_page }}');">{{ _localized('main.load_more_updates') }}</a>
    {% endif %}
</div>