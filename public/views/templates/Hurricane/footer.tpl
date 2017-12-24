                <br class="clear" />
            </div>

            <div id="copyright">
                <div class="col33">{$copyright}{if not $locale_copyright eq 'none'}<br />{$locale_copyright}{/if}</div>
                <div class="col33 align_center"><a id="to_top" href="#wrapper" title="{$locale->read('main', 'to_top')}"><i id="to_top_arrow" class="fa fa-arrow-up"></i></a></div>
                <div class="col33 align_right">
                    <form action="{$url}/index.php/locale" method="post">
                        <label for="locale_switcher">{$locale->read('main', 'locale')}:</label>
                        <select name="code" id="locale_switcher">
                        {foreach $locale_continents continent}
                            <optgroup label="{$continent['label']}">
                            {foreach $continent['locales'] lang}
                                <option value="{$lang['code']}"{if not $lang['selected'] eq ''} selected{/if}>{$lang['native']}</option>
                            {/foreach}
                            </optgroup>
                        {/foreach}
                        </select>
                    </form>
                </div>
                <br class="clear" />
            </div>
        </div>
        {if not $debug_body eq ''}
            {$debug_body}
        {/if}
    </body>
</html>
