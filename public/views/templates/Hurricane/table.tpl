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