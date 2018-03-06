<div class="col60">
    <div id="about_page_logo">
        <div class="float_left col50"><h1>DraiWiki {{ wiki_version }}</h1></div>
        <div class="float_left col50 align_right"><img src="{{ base_url }}/DWLogo.png" alt="DraiWiki" /></div>
        <br class="clear" />
    </div>
    <p>{{ _localized('main.about_introduction') }}</p>
    <p>{{ _localized('main.about_thank_you') }}</p>

    <hr class="upp_bot_mar" />

    <h2>{{ _localized('main.team_members') }}</h2>
    <ul>
        {% for member in team_members %}
            <li>{{ member.name }}
                {% if member.website is not empty %}[<a href="{{ member.website }}" target="_blank">{{ _localized('main.website') }}</a>]{% endif %}
                {% if member.email is not empty %}[<a href="mailto:{{ member.email }}">{{ _localized('main.email') }}</a>]{% endif %}
            </li>
        {% endfor %}
    </ul>

    <h2>{{ _localized('main.libraries') }}</h2>
    <p>{{ _localized('main.about_libraries') }}</p>
    <ul>
        {% for package in libraries %}
            <li><strong>{{ package.name }} - </strong><a href="{{ package.href }}" target="_blank">{{ package.href }}</a></li>
        {% endfor %}
    </ul>

    <hr class="upp_bot_mar" />

    <p>{{ _localized('main.about_href') }}</p>
</div>