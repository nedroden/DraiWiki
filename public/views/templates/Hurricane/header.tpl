<!DOCTYPE HTML>
<html>
    <head>
        <title>{{ title }} | {{ wiki_name }}{% if slogan is not empty %} | {{ slogan }} {% endif %}</title>

        <link href="{{ node_url }}/fontface-source-sans-pro/css/source-sans-pro.min.css" rel="stylesheet">
        <link href="{{ node_url }}/notosans-fontface/css/notosans-fontface.css" rel="stylesheet">
        <link href="{{ node_url }}/roboto-fontface/css/roboto/roboto-fontface.css" rel="stylesheet">

        <script type="text/javascript">
            var please_confirm = '{{ _localized('script.please_confirm') }}';
            var ok = '{{ _localized('script.ok') }}';
            var failed_to_retrieve_ajax_data = '{{ _localized('script.failed_to_retrieve_ajax_data') }}';
            var showing_results = '{{ _localized('script.showing_results') }}';
            var dw_url = '{{ url }}';
        </script>

        <link rel="stylesheet" type="text/css" href="{{ skin_url }}main" />
        <link rel="stylesheet" type="text/css" href="{{ skin_url }}shared" />
        <link rel="stylesheet" type="text/css" href="{{ node_url }}/select2/dist/css/select2.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ node_url}}/zebra_dialog/dist/css/flat/zebra_dialog.css" />
        <link rel="stylesheet" type="text/css" href="{{ node_url}}/font-awesome/css/font-awesome.min.css" />
        <link rel="icon" href="{{ url }}/favicon.png" sizes="16x16" type="image/png" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<script type="text/javascript" src="{{ node_url }}/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/select2/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/zebra_dialog/dist/zebra_dialog.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/sprintf-js/dist/sprintf.min.js"></script>

        <script type="text/javascript" src="{{ script_url }}/dw-controller.js"></script>
        <script type="text/javascript" src="{{ script_url }}/ajax.js"></script>
        <script type="text/javascript" src="{{ script_url }}/table.js"></script>

        {% if display_cookie_warning %}
        <link rel="stylesheet" type="text/css" href="{{ node_url }}/cookieconsent/build/cookieconsent.min.css" />
        <script type="text/javascript" src="{{ node_url }}/cookieconsent/build/cookieconsent.min.js"></script>
        <script type="text/javascript" src="{{ script_url }}/cookie.js"></script>

        <script type="text/javascript">
            const cookie_header = '{{ _localized('script.cookie_header') }}';
            const cookie_explained = '{{ _localized('script.cookie_explained') }}';
            const cookie_click_here = '{{ _localized('script.cookie_click_here') }}';
        </script>
        {% endif %}

        {% if debug_head is not empty %}
            {{ debug_head }}
        {% endif %}

        {% if header is not empty %}
            {{ header }}
        {% endif %}

        {% if module_headers is not empty %}
            {{ module_headers }}
        {% endif %}
    </head>
    <body>
        <div id="wrapper">
            <div id="header_section">
                <div id="header">
                    <a href="{{ url }}" target="_self">{{ wiki_name }}</a>
                    {% if slogan is not empty %}
                    <span>{{ slogan }}</span>
                    {% endif %}
                </div>

                <div id="topbar">
                    <div id="menu">
                        {% for item in menu %}
                            <a href="{{ item.href }}">{{ item.label }}</a>
                        {% endfor %}
                    </div>
                    <div id="userinfo">
                        {% if user.isGuest() %}
                            {{ _localized('main.hello', _localized('auth.guest')) }}
                        {% else %}
                            {{ _localized('main.hello', user.getFirstName()) }}
                        {% endif %}
                    </div>
                    <br class="clear" />
                </div>

            </div>
            <div id="content_header">
                <div class="col60">
                    {{ title }}
                </div>
                <div class="col40 align_right" id="header_search">
                    <form action="{{ url }}/index.php/find" method="post">
                        <input type="text" name="terms" placeholder="{{ _localized('main.search_for') }}" />
                        <input type="submit" value="{{ _localized('main.go') }}" />
                    </form>
                </div>
                <br class="clear" />
            </div>
            <div id="content">
