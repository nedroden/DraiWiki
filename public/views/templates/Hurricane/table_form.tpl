<div class="form_table">
    {foreach $rows item}
        {if not $item['is_header'] eq ''}
            <h1>{$item['label']}</h1>
        {else}
            <div>
                <input type="hidden" name="form_submitted" value="1"/>
                <label for="{$item['name']}">
                    {$item['label']}
                </label><br />
                <span>{$item['description']}</span><br />
                {if $item['input_type'] eq 'text'}
                    <input type="text" name="{$item['name']}"{if not $item['value'] eq ''} value="{$item['value']}"{/if} />
                {elseif $item['input_type'] eq 'checkbox'}
                    <p><input type="checkbox" name="{$item['name']}"{if $item['value'] eq 1} checked{/if} />{if not $item['input_description'] eq ''} {$item['input_description']}{/if}</p>
                {/if}
            </div>
        {/if}
    {/foreach}
</div>