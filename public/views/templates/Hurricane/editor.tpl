<div id="form_area" class="editor_form">
    <link rel="stylesheet" type="text/css" href="{{ node_url }}/simplemde/dist/simplemde.min.css" />
    <script type="text/javascript" src="{{ node_url }}/simplemde/dist/simplemde.min.js"></script>

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <h1 class="article_title">{{ _localized('article.editing') }} {{ title }}</h1>
    <div class="section_content">
        <form action="{{ action }}" method="post">
            <input type="hidden" name="id" value="{{ id }}" />
            <label 	for="title" class="text_bold">
                {{ _localized('editor.title') }}
            </label>
            <input 	  type="text"
                      name="title"
                      value="{{ title }}"
                      class="wide" /><br /><br />
            <textarea id="editor" name="body">{{ body_text_area }}</textarea>
            <input type="submit" value="{{ _localized('editor.save') }}" />
        </form>

        <script type="text/javascript">
            var simplemde = new SimpleMDE({
                element: document.getElementById("editor"),
                tabSize: 8
            });
        </script>
    </div>
</div>