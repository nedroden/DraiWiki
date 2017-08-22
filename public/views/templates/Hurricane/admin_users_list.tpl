            <div class="content_section">
                {if not $errors eq ''}
                    <div class="message_box error">
                        <ul>
                            {foreach $errors msg}
                                <li>{$msg}</li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}

                {$table}
            </div>

            <script type="text/javascript">activateTable('/management/users/ajax/getlist');</script>