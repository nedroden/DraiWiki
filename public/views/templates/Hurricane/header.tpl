<!DOCTYPE HTML>
<html>
    <head>
        <title>{$title} | {$wiki_name}</title>
        <link rel="stylesheet" type="text/css" href="{$skin_url}main" />
        <link rel="icon" href="{$url}/favicon.png" sizes="16x16" type="image/png" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- <script type="text/javascript" src="{$node_url}/angular/angular.js"></script>
        <script type="text/javascript" src="{$script_url}/dw-controller.js"></script> //-->
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
                    <a href="{$url}">{$wiki_name}</a>
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
