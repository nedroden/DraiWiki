<!DOCTYPE HTML>
<html>
    <head>
        <title>{$title} | {$wiki_name}</title>

        <script type="text/javascript">
            var please_confirm = '{$locale->read('script', 'please_confirm')}';
            var ok = '{$locale->read('script', 'ok')}';
            var failed_to_retrieve_ajax_data = '{$locale->read('script', 'failed_to_retrieve_ajax_data')}';
        </script>

        <link rel="stylesheet" type="text/css" href="{$skin_url}main" />
        <link rel="stylesheet" type="text/css" href="{$node_url}/zebra_dialog/dist/css/flat/zebra_dialog.css" />
        <link rel="icon" href="{$url}/favicon.png" sizes="16x16" type="image/png" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<script type="text/javascript" src="{$node_url}/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="{$node_url}/zebra_dialog/dist/zebra_dialog.min.js"></script>
        <script type="text/javascript" src="{$script_url}/dw-controller.js"></script>

        {if $display_cookie_warning}
        <link rel="stylesheet" type="text/css" href="{$node_url}/cookieconsent/build/cookieconsent.min.css" />
        <script type="text/javascript" src="{$node_url}/cookieconsent/build/cookieconsent.min.js"></script>
        <script type="text/javascript" src="{$script_url}/cookie.js"></script>

        <script type="text/javascript">
            var cookie_header = '{$locale->read('script', 'cookie_header')}';
            var cookie_explained = '{$locale->read('script', 'cookie_explained')}';
            var cookie_click_here = '{$locale->read('script', 'cookie_click_here')}';
        </script>
        {/if}
        {if not $debug_head eq ''}
            {$debug_head}
        {/if}
    </head>
    <body>
        <div id="wrapper">
            <div id="header_section">
                <div id="topbar">
                    <div id="menu">
                        {foreach $menu, item}
                            <a href="{$item.href}">{$item.label}</a>
                        {/foreach}
                    </div>
                    <div id="userinfo">
                        {$locale->read('main', 'hello', true)}
                    </div>
                    <br class="clear" />
                </div>

                <div id="header">
                    <a href="{$url}" target="_self">{$wiki_name}</a>
                </div>

                <div id="content_header">
                    <div class="col60">
                        {$title}
                    </div>
                    <div class="col40 align_right" id="header_search">
                        <form action="{$url}/index.php" method="get">
                            <input type="text" placeholder="{$locale->read('main', 'search_for')}" />
                            <input type="submit" value="{$locale->read('main', 'go')}" />
                        </form>
                    </div>
                    <br class="clear"/>
                </div>
            </div>
            <div id="content">
