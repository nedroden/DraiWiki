<div class="col66">
    <h1 class="article_title">{{ title }}</h1>
    {{ history_table | raw }}

    <script type="text/javascript">activateTable('/article/{$title_safe}/history/ajax/getlist');</script>
</div>