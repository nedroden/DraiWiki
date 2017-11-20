<div id="translation_assignment" class="col60">
    <h1>{$locale->read('article', 'assign_translations')}</h1>
    <div class="message_box info">
        {$locale->read('article', 'assign_translations_what')}
    </div>

    <form id="assign_translations_form" action="{$action}" method="post">
        <input type="text" id="assign_translations_box" name="terms" placeholder="{$locale->read('find', 'enter_a_search_term_short')}" />

        <input type="hidden" value="" id="article_id" name="article_id" />

        <div id="resultio">
            {$locale->read('find', 'results_will_appear_here')}
        </div>
    </form>

    {if $can_declare_independence}
        <br /><br />

        <h1>{$locale->read('article', 'declare_independence')}</h1>
        <p>{$remove_text}</p>
    {/if}

</div>