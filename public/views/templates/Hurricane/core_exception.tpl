<!DOCTYPE HTML>
<html>
    <head>
        <title>{{ title }}</title>
        <link rel="stylesheet" type="text/css" href="{{ skin_url }}exception" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>

    <body>
        <div id="wrapper">
            <div class="float_left">
                <h1>{{ title }}</h1>
            </div>
            <div class="float_right">
                <img src="DWLogo.png" alt="DraiWiki" />
            </div>
            <br class="clear" />

            <p>{{ body }}</p>

            {% if detailed is not empty %}<hr />
                <p>{{ detailed }}</p>
                <div id="backtrace">
                    {% for row in backtrace %}
                        {{ row }}<br />
                    {% endfor %}
                </div>
            {% endif %}
        </div>
        <div id="copyright">
            {{ copyright }}
        </div>
    </body>
</html>