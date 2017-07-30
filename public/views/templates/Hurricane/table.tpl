{if $type eq 'info_table'}
    <div class="info_table">
        {foreach $rows cell}
            <div>
                <span>{$cell[0]}</span>
                <span>{$cell[1]}</span>
            </div>
        {/foreach}
        <br class="clear" />
    </div>
    {else}
    <div class="count float_left"></div><div class="pagination float_right"></div><br class="clear" />
    <table id="{$id}" class="{$type}">
        <thead>
            {foreach $columns column}
                <th>{$column}</th>
            {/foreach}
        </thead>
        <tbody>
            {foreach $rows row}
                <tr>
                    {foreach $row cell}
                        <td>{$cell}</td>
                    {/foreach}
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div class="count float_left"></div><div class="pagination float_right"></div><br class="clear" />
{/if}