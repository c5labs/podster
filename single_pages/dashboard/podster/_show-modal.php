<div style="display: none">
    <div id="ccm-dialog-add-show" class="ccm-ui">
        <div style="max-height: 70vh;">
            <?php include('_show-form.php'); ?>
        </div>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
            <button class="btn btn-primary pull-right" onclick="$('#ccm-dialog-add-show form').submit()"><?=t('Add Show')?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('button[data-dialog=add-show]').on('click', function() {
            jQuery.fn.dialog.open({
                element: '#ccm-dialog-add-show',
                modal: true,
                width: 620,
                title: <?=json_encode(t("Add Show"))?>,
                height: 'auto'
            });
        });
    });
</script>