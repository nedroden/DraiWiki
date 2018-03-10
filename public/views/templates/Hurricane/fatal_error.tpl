<!DOCTYPE HTML>
<html>
<head>
    <title>{{ title }}</title>
    <link rel="stylesheet" type="text/css" href="{{ skin_url }}exception" />
    <link rel="stylesheet" type="text/css" href="{{ skin_url }}regular_error" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
<div id="wrapper">
    <h1>{{ title }}</h1>
    <p>{{ body }}</p>

    {% if detailed is not empty %}<hr />
        <p>{{ detailed }}</p>
    {% endif %}
</div>
<div id="copyright">
    {{ copyright }}
</div>
</body>
</html>