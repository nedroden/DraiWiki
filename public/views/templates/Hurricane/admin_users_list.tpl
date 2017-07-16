            <script type="text/javascript" src="{$node_url}/datatables/media/js/jquery.dataTables.min.js"></script>
            <link rel="stylesheet" type="text/css" href="{$node_url}/datatables/media/css/jquery.dataTables.min.css" />

            <div class="content_section">
                <table id="user_list" class="display" data-page-length="25">
                    <thead>
                        <tr>
                            <th>{$locale->read('management', 'username')}</th>
                            <th>{$locale->read('management', 'first_name')}</th>
                            <th>{$locale->read('management', 'last_name')}</th>
                            <th>{$locale->read('management', 'email_address')}</th>
                            <th>{$locale->read('management', 'sex')}</th>
                        </tr>
                    </thead>
                </table>

                <script type="text/javascript">loadUserListAjax();</script>
            </div>