<div id="form_area">
    <link rel="stylesheet" type="text/css" href="{$node_url}/simplemde/dist/simplemde.min.css" />
    <script type="text/javascript" src="{$node_url}/simplemde/dist/simplemde.min.js"></script>

    <div class="section_header">
        {$locale->read('tools', 'upload_an_image')}
    </div>

    {if not $errors eq ''}
        <div class="message_box error">
            <ul>
                {foreach $errors msg}
                    <li>{$msg}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <div class="section_content">
        <form action="{$action}" enctype="multipart/form-data" method="post">
            <p>{$locale->read('tools', 'you_can_upload_images_here')}</p>
            <label 	for="file"{if not $errors['file'] eq ''} class="contains_error"{/if}>
                {$locale->read('tools', 'file')}
            </label>
            <input 	type="file"
                    name="file" /><br />
            <label 	for="editor"{if not $errors['description'] eq ''} class="contains_error"{/if}>
                {$locale->read('tools', 'description')}
            </label>
            <textarea id="editor" name="description"></textarea>
            <input type="submit" value="{$locale->read('tools', 'upload')}" />
        </form>
    </div>

    <script type="text/javascript">
        var simplemde = new SimpleMDE({
            element: document.getElementById("editor"),
            tabSize: 8
        });
    </script>
</div>