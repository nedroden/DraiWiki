<div class="form_table">
    {foreach $rows item}
        {if not $item['is_header'] eq ''}
            <h1>{$item['label']}</h1>
        {else}
            <div>
                <label for="{$item['name']}">
                    {$item['label']}
                </label><br />
                <span>{$item['description']}</span>
                {if $item['input_type'] eq 'text'}
                    <input type="text" name="{$item['name']}"{if not $item['value'] eq ''} value="{$item['value']}"{/if} />
                {/if}
            </div>
        {/if}
    {/foreach}
</div>