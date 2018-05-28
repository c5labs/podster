<?php if (isset($flashMessage)) {
    ?><script>
    $(document).ready(function() {
        ConcreteAlert.notify({
            message: <?=json_encode($flashMessage)?>
        });
    });
    </script><?php
} 
 require_once('_show-modal.php'); ?>
<div class="ccm-dashboard-header-buttons btn-group">
    <button data-dialog="add-show" class="btn btn-primary"><i class="fa fa-plus"></i> <?=t("New Show")?></button>
</div>
<div class="ccm-dashboard-content-full" style="margin-top: -30px;">
    <div class="table-responsive">
        <table class="ccm-search-results-table ccm-search-results-table-icon">
            <thead>
                <tr>
                    <th style="padding-left: 0;"></th>
                    <th style="padding-left: 0; width: 70px;"><span class="fa fa-image"></span></th>
                    <th style="width: 50%; padding: 15px 0px 15px 15px;">Name</th>
                    <th style="padding: 15px 0px 15px 15px; text-align: center;"># Episodes</th>
                    <th style="padding: 15px 0px 15px 15px; text-align: center;">Author</th>
                    <th style="padding: 15px; text-align: center;"><span class="fa fa-eye"></span></th>
                    <th style="padding: 15px; text-align: center;"><span class="fa fa-download"></span></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($shows->numRows() > 0) { ?>
                <?php foreach ($shows as $show) { ?>
                <tr>
                    <td style="padding-left: 15px;"></td>
                    <td style="width: 70px;">
                        <div style="width: 50px; height: 50px; background: #ccc; <?php echo $hp->getCoverCss($show); ?> background-size: cover;"></div>
                    </td>
                    <td style="font-size: 1rem;">
                        <strong><a href="<?php echo View::url('/dashboard/podster/show/' . $show['id']); ?>"><?php echo $show['title']; ?></a></strong>
                        <p style="font-size: .8rem; margin: 0;"><?php echo $show['subTitle']; ?></p>
                    </td>
                    <td class="text-center"><?php echo $show['numEpisodes']; ?></td>
                    <td class="text-center"><?php echo $show['author']; ?></td>
                    <td class="text-center"><?php echo $st->getFeedViews($show); ?></td>
                    <td class="text-center"><?php echo $st->getShowDownloads($show); ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td style="padding-left: 15px;"></td>
                    <td colspan="6">No shows.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php include('_footer.php'); ?>
</div>