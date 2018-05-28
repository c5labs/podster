<div class="form-group show-advanced episode-advanced hidden">
    <label for="linkType"><?php echo t('Link')?></label>
    <?php echo $form->select('linkType', ['page' => 'Another Page', 'external' => 'External Link'], $linkType ?: 'none', ['class' => 'form-control']); ?>
</div>

<div class="show-advanced episode-advanced hidden">
    <div id="manualLink" class="form-group hidden">
        <?php echo $form->text('linkUrl', $linkUrl, ['class' => 'form-control', 'placeholder' => 'https://mysite.com/podcast']); ?>
    </div>

    <div id="pageSelector" class="form-group hidden">
        <?php echo $ps->selectPage('linkCID', $linkCID); ?>
    </div>
</div>

<script>
    $(function() {            
        $('select[name=linkType]').change(function() {
            var type = $(this).val();

            if (type === 'external') {
                $('#manualLink').removeClass('hidden');
                $('#pageSelector').addClass('hidden');
                $('#pageSelector .ccm-item-selector-clear').trigger('click');
            } else {
                $('#manualLink').addClass('hidden');
                $('#pageSelector').removeClass('hidden');
                $('input[name=linkUrl]').val('');
            }
        }).trigger('change');
    });
</script>