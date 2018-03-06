<div id="form_area">
    <link rel="stylesheet" type="text/css" href="{{ node_url }}/simplemde/dist/simplemde.min.css" />
    <script type="text/javascript" src="{{ node_url }}/simplemde/dist/simplemde.min.js"></script>

    <div class="section_header">
        {{ _localized('tools.upload_an_image') }}
    </div>

    {% if errors is not empty %}
        <div class="message_box error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <div class="section_content">
        <form action="{{ action }}" enctype="multipart/form-data" method="post">
            <p>{{ _localized('tools.you_can_upload_images_here') }}</p>
            <label 	for="file"{% if errors.file is not empty %} class="contains_error"{% endif %}
                {{ _localized('tools.file') }}
            </label>
            <input 	type="file"
                    name="file" /><br />
            <label 	for="editor"{% if errors.description is not empty %} class="contains_error"{% endif %}>
                {{ _localized('tools.description') }}
            </label>
            <textarea id="editor" name="description"></textarea>
            <input type="submit" value="{{ _localized('tools.upload') }}" />
        </form>
    </div>

    <script type="text/javascript">
        var simplemde = new SimpleMDE({
            element: document.getElementById("editor"),
            tabSize: 8
        });
    </script>
</div>