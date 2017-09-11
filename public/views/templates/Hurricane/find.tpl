<div class="col60">

    {if not $errors eq ''}
        <div class="message_box error">
            <ul>
                {foreach $errors msg}
                    <li>{$msg}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <form action="{$action}" method="post">
        <input type="text" id="search" name="terms" placeholder="{$locale->read('find', 'enter_a_search_term')}" />
    </form>

    {if not $articles eq ''}
        <div class="message_box">{$locale->read('find', 'searched_for')} <strong>{$unparsed_search_terms}</strong>. {$number_of_results}</div>
        <div id="results">
            {foreach $articles article}
                <div class="search_result">
                    <h1><a href="{$article['href']}" target="_self">{$article['title']}</a></h1>
                    {$article['body_shortened']}
                </div>
            {/foreach}
        </div>

        {if $show_load_more}
            <a id="more_results" href="javascript:void(0);" onclick="loadMoreSearchResults('{$max_results}', '{$search_terms}');">{$locale->read('find', 'load_more_results')}</a>
        {/if}
    {elseif $has_loaded_articles}
        <div class="message_box notice">
            {$locale->read('find', 'no_results_were_found')}
        </div>
    {/if}
</div>