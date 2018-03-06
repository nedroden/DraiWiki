<!DOCTYPE HTML>
<html>
    <head>
        <title>{{ title }} | {{ wiki_name }}{% if slogan is not empty %} | {{ slogan }} {% endif %}</title>
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

        <script type="text/javascript">
            var please_confirm = '{{ _localized('script.please_confirm') }}';
            var ok = '{{ _localized('script.ok') }}';
            var table_search = '{{ _localized('script.table_search') }}';
            var failed_to_retrieve_ajax_data = '{{ _localized('script.failed_to_retrieve_ajax_data') }}';

            var showing_results = '{{ _localized('script.showing_results') }}';

            var dw_url = '{{ url }}';
        </script>

        <link rel="stylesheet" type="text/css" href="{{ skin_url }}admin" />
        <link rel="stylesheet" type="text/css" href="{{ node_url }}/select2/dist/css/select2.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ node_url }}/zebra_dialog/dist/css/flat/zebra_dialog.css" />
        <link rel="stylesheet" type="text/css" href="{{ node_url }}/font-awesome/css/font-awesome.min.css" />
        <link rel="icon" href="{{ url }}/favicon.png" sizes="16x16" type="image/png" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <script type="text/javascript" src="{{ node_url }}/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/select2/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/zebra_dialog/dist/zebra_dialog.min.js"></script>
        <script type="text/javascript" src="{{ node_url }}/sprintf-js/dist/sprintf.min.js"></script>

        <script type="text/javascript" src="{{ script_url }}/actionbar.js"></script>
        <script type="text/javascript" src="{{ script_url }}/ajax.js"></script>
        <script type="text/javascript" src="{{ script_url }}/dw-controller.js"></script>
        <script type="text/javascript" src="{{ script_url }}/management.js"></script>
        <script type="text/javascript" src="{{ script_url }}/table.js"></script>
        {% if debug_head is not empty %}
            {{ debug_head }}
        {% endif %}
    </head>
    <body>
        <div id="wrapper">
            <div id="header_section">
                <div id="topbar">
                    <div id="siteinfo">
                        <a href="{{ url }}/index.php/management" target="_self">{{ wiki_name }}</a><span> | {{ _localized('management.management_panel') }}</span>
                    </div>
                    <div id="userinfo">
                        {{ _localized('main.hello', user.getFirstName()) }}
                    </div>
                    <br class="clear" />
                </div>
            </div>
            <div id="sidebar" class="col20">
                {% for item in sidebar %}
                    <div class="sidebar_header">{{ item.label }}</div>
                    <ul>
                        {% for subitem in item.items %}
                            <li>{% if subitem.icon is not empty %}<i class="fa {{ subitem.icon }}"></i> {% endif %}<a{% if subitem.request_confirm is not empty %} onclick="requestConfirm('{{ subitem.href }}');" href="javascript:void(0);"{% else %} href="{{ subitem.href }}"{% endif %} target="_self"{% if subitem.id is not empty %} id="{{ subitem.id }}"{% endif %}{% if subitem.active is not empty %} class="active"{% endif %}>{{ subitem.label }}</a></li>
                        {% endfor %}
                    </ul>
                {% endfor %}
            </div>
            <div id="content" class="col80 float_right">
                <div id="page_description">
                    <h1>{{ title }}</h1>
                    {{ page_description }}
                </div>
