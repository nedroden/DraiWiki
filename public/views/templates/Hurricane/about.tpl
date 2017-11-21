<div class="col60">
    <div id="about_page_logo">
        <div class="float_left col50"><h1>DraiWiki {$wiki_version}</h1></div>
        <div class="float_left col50 align_right"><img src="{$base_url}/DWLogo.png" alt="DraiWiki" /></div>
        <br class="clear" />
    </div>
    <p>{$locale->read('main', 'about_introduction')}</p>
    <p>{$locale->read('main', 'about_thank_you')}</p>

    <hr class="upp_bot_mar" />

    <h2>{$locale->read('main', 'team_members')}</h2>
    <ul>
        {foreach $team_members member}
            <li>{$member['name']}
                {if not $member['website'] eq ''}[<a href="{$member['website']}" target="_blank">{$locale->read('main', 'website')}</a>]{/if}
                {if not $member['email'] eq ''}[<a href="mailto:{$member['email']}">{$locale->read('main', 'email')}</a>]{/if}
            </li>
        {/foreach}
    </ul>

    <h2>{$locale->read('main', 'libraries')}</h2>
    <p>{$locale->read('main', 'about_libraries')}</p>
    <ul>
        {foreach $libraries package}
            <li><strong>{$package['name']} - </strong><a href="{$package['href']}" target="_blank">{$package['href']}</a></li>
        {/foreach}
    </ul>

    <hr class="upp_bot_mar" />

    <p>{$locale->read('main', 'about_href')}</p>
</div>