<div class="content_section">
    <script type="text/javascript" src="{$node_url}/chart.js/dist/Chart.min.js"></script>
    <canvas id="number_edits" height="40px"></canvas>
    <script type="text/javascript">
        var data = {
            labels: [{$edits_this_week['keys']}],
            datasets: [{
                    label: '{$locale->read('management', 'number_of_edits')}',
                    data: [{$edits_this_week['values']}],
                    backgroundColor: '#d1cc87',
                    borderColor: '#ede474',
                    fill: false,
                    lineTension: 0,
                    pointRadius: 3
                }
            ]
        };

        var options = {
            title: {
                display: true,
                position: 'top',
                text: '{$locale->read('management', 'number_of_edits_last_seven_days')}',
                fontSize: 18
            },

            legend: {
                display: false
            }
        };

        var linechart_edit = new Chart($('#number_edits'), {
            type: 'line',
            data: data,
            options: options
        });
    </script>
</div>
<div class="section_description">
    <h2>{$locale->read('management', 'recent_edits')}</h2>
    {$locale->read('management', 'recent_edits_description')}
</div>
<div class="content_section">
    {$recent_edits_table}

    <script type="text/javascript">activateTable('/management/dashboard/ajax/getrecentedits');</script>
</div>
<div class="section_description">
    <h2>{$locale->read('management', 'server_information')}</h2>
    {$locale->read('management', 'server_information_description')}
</div>
<div class="content_section">
    <div id="server_information">
        <div class="table_header">
            <span>{$locale->read('management', 'webserver')}</span>
            <span></span>
        </div>
        <div>
            <span>{$locale->read('management', 'server_software')}</span>
            <span>{$server_software}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'php_version')}</span>
            <span>{$php_version}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'db_version')}</span>
            <span>{$mysql_version}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'loaded_extensions')}</span>
            <span>{$loaded_extensions}</span>
        </div>

        <div class="table_header">
            <span>{$locale->read('management', 'wiki_information')}</span>
            <span></span>
        </div>
        <div>
            <span>{$locale->read('management', 'draiwiki_version')}</span>
            <span>{$draiwiki_version}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'default_locale')}</span>
            <span>{$default_locale}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'default_templates')}</span>
            <span>{$default_templates}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'default_skins')}</span>
            <span>{$default_skins}</span>
        </div>
        <div>
            <span>{$locale->read('management', 'default_images')}</span>
            <span>{$default_images}</span>
        </div>

        <br class="clear" />
    </div>
</div>