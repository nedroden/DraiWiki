                <br class="clear" />
            </div>

            <div id="copyright">
                <div class="col33">{$copyright}{if not $locale_copyright eq 'none'}<br />{$locale_copyright}{/if}</div>
                <div class="col33 align_center"><a id="to_top" href="#wrapper">{$locale->read('main', 'to_top')}</a></div>
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

            <div id="dw-about" style="display: none;">
                <h2>DraiWiki {$wiki_version}</h2>
                <p>{$locale->read('main', 'about_introduction')}</p>
                <p>{$locale->read('main', 'about_thank_you')}</p>

                <hr class="upp_bot_mar" />

                {foreach $teams team}
                    <strong>{$team['label']}</strong>
                    <ul>
                        {foreach $team['members'] member}
                            <li>{$member['name']}
                                {if not $member['website'] eq ''}[<a href="{$member['website']}" target="_blank">{$locale->read('main', 'website')}</a>]{/if}
                                {if not $member['email'] eq ''}[<a href="mailto:{$member['email']}">{$locale->read('main', 'email')}</a>]{/if}
                            </li>
                        {/foreach}
                    </ul>
                {/foreach}

                <p>{$locale->read('main', 'about_libraries')}</p>
                <ul>
                    {foreach $packages package}
                        <li><strong>{$package['name']} - </strong><a href="{$package['href']}" target="_blank">{$package['href']}</a></li>
                    {/foreach}
                </ul>

                <hr class="upp_bot_mar" />

                <p>{$locale->read('main', 'about_href')}</p>
            </div>
        </div>
        {if not $debug_body eq ''}
            {$debug_body}
        {/if}
    </body>
</html>
