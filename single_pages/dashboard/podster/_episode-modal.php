<div style="display: none">
    <div id="ccm-dialog-add-episode" class="ccm-ui">
        <div style="max-height: 70vh;">
            <?php include('_episode-form.php'); ?>
        </div>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
            <button class="btn btn-primary pull-right" onclick="$('#ccm-dialog-add-episode form').submit()"><?=t('Add Episode')?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('button[data-dialog=add-episode]').on('click', function() {
            jQuery.fn.dialog.open({
                element: '#ccm-dialog-add-episode',
                modal: true,
                width: 620,
                title: <?=json_encode(t("Add Episode"))?>,
                height: 'auto'
            });
        });
    });
</script>