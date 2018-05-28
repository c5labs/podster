<div class="ccm-dashboard-header-buttons btn-group">
    <a href="<?php echo View::url('/dashboard/podster/show/' . $episode['showID']); ?>" class="btn btn-default">Back</a>
</div>
<script>
    window.podstartAllFieldsVisible = true;
</script>
<div class="podster-content">
    <div class="row">
        <div class="col-sm-12">
            <?php include('_episode-form.php'); ?>

            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">
                    <button class="pull-right btn btn-success" type="submit" onclick="$('.podster-content form').submit()"><?=t('Save Changes')?></button>
                </div>
            </div>
        </div>
    </div>
</div>