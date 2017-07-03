                <br class="clear" />
            </div>
            <div id="copyright">
                <div class="col33">{$copyright}</div>
                <div class="col33 align_center"><a href="#wrapper">{$locale->read('main', 'to_top')}</a></div>
                <div class="col33 align_right">
                    <strong>{$locale->read('main', 'locale')}:</strong> {$locale_native}{if not copyright eq 'none'}<br />{$locale_copyright}{/if}
                </div>
                <br class="clear" />
            </div>
        </div>
    </body>
</html>
