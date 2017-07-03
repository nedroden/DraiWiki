                <br class="clear" />
            </div>

            <div id="copyright">
                <div class="col33">{$copyright}</div>
                <div class="col33 align_center"><a href="#wrapper">{$locale->read('main', 'to_top')}</a></div>
                <div class="col33 align_right">
                    <strong>{$locale->read('main', 'locale')}:</strong> {$locale_native}{if not copyright eq 'none'}<br />{$locale_copyright}{/if}
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

                <hr class="upp_bot_mar" />

                <p>{$locale->read('main', 'about_href')}</p>
            </div>
        </div>
    </body>
</html>
