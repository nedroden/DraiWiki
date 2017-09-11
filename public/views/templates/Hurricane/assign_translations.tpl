<div id="translation_assignment" class="col60">
    <h1>{$locale->read('article', 'assign_translations')}</h1>
    <div class="message_box info">
        {$locale->read('article', 'assign_translations_what')}
    </div>
    <div class="col50 float_left">
        <form action="{$action}" method="post">
            <input type="text" id="search" name="terms" placeholder="{$locale->read('find', 'enter_a_search_term_short')}" />
        </form>
    </div>
    <div class="col50 float_right">
        <div id="results">
            {$locale->read('find', 'results_will_appear_here')}
        </div>
    </div>
    <br class="clear" />
</div>