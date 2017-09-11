<!DOCTYPE HTML>
<html>
    <head>
        <title>{$title} | {$wiki_name}</title>
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

        <script type="text/javascript">
            var please_confirm = '{$locale->read('script', 'please_confirm')}';
            var ok = '{$locale->read('script', 'ok')}';
            var table_search = '{$locale->read('script', 'table_search')}';
            var failed_to_retrieve_ajax_data = '{$locale->read('script', 'failed_to_retrieve_ajax_data')}';

            var showing_results = '{$locale->read('script', 'showing_results')}';

            var dw_url = '{$url}';
        </script>

        <link rel="stylesheet" type="text/css" href="{$skin_url}admin" />
        <link rel="stylesheet" type="text/css" href="{$node_url}/select2/dist/css/select2.min.css" />
        <link rel="stylesheet" type="text/css" href="{$node_url}/zebra_dialog/dist/css/flat/zebra_dialog.css" />
        <link rel="stylesheet" type="text/css" href="{$node_url}/font-awesome/css/font-awesome.min.css" />
        <link rel="icon" href="{$url}/favicon.png" sizes="16x16" type="image/png" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <script type="text/javascript" src="{$node_url}/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="{$node_url}/select2/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="{$node_url}/zebra_dialog/dist/zebra_dialog.min.js"></script>
        <script type="text/javascript" src="{$node_url}/sprintf-js/dist/sprintf.min.js"></script>

        <script type="text/javascript" src="{$script_url}/actionbar.js"></script>
        <script type="text/javascript" src="{$script_url}/ajax.js"></script>
        <script type="text/javascript" src="{$script_url}/dw-controller.js"></script>
        <script type="text/javascript" src="{$script_url}/management.js"></script>
        <script type="text/javascript" src="{$script_url}/table.js"></script>
        {if not $debug_head eq ''}
            {$debug_head}
        {/if}
    </head>
    <body>
        <div id="wrapper">
            <div id="header_section">
                <div id="topbar">
                    <div id="siteinfo">
                        <a href="{$url}/index.php/management" target="_self">{$wiki_name}</a><span> | {$locale->read('management', 'management_panel')}</span>
                    </div>
                    <div id="userinfo">
                        {$locale->read('main', 'hello', true)}
                    </div>
                    <br class="clear" />
                </div>
            </div>
            <div id="sidebar" class="col20">
                {foreach $sidebar item}
                    <div class="sidebar_header">{$item['label']}</div>
                    <ul>
                        {foreach $item['items'] subitem}
                            <li>{if not $subitem['icon'] == ''}<i class="fa {$subitem['icon']}"></i> {/if}<a{if not $subitem['request_confirm'] == ''} onclick="requestConfirm('{$subitem['href']}');" href="javascript:void(0);"{else} href="{$subitem['href']}"{/if} target="_self"{if not $subitem['id'] eq ''} id="{$subitem['id']}"{/if}{if not $subitem['active'] == ''} class="active"{/if}>{$subitem['label']}</a></li>
                        {/foreach}
                    </ul>
                {/foreach}
            </div>
            <div id="content" class="col80 float_right">
                <div id="page_description">
                    <h1>{$title}</h1>
                    {$page_description}
                </div>
