
<style>
  .dont-break-out {

  /* These are technically the same, but use both */
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;

}

.truncate {
  width: 300px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
<?php if (isset($flashMessage)) {
    ?><script>
    $(document).ready(function() {
        ConcreteAlert.notify({
            message: <?=json_encode($flashMessage)?>
        });
    });
    </script><?php
} 
 require_once('_episode-modal.php'); ?>
<div class="ccm-dashboard-header-buttons btn-group">
    <a href="<?php echo View::url('/dashboard/podster/edit_show/' . $show['id']); ?>" class="btn btn-default">Edit</a>
    <a href="<?php echo View::url('/dashboard/podster/delete_show/' . $show['id']); ?>?ccm_token=<?php echo urlencode($this->controller->token->generate('delete_show')); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete \'<?php echo $show['title']; ?>\' and all of it\'s episodes?');">Delete</a>
</div>
<div class="row">
    <div class="col-sm-3" style="font-size: .9rem;">
        <img src="<?php echo $hp->getCoverUrl($show); ?>" alt="<?php echo $show['title']; ?>" style="max-width: 100%;">
       <p><?php echo $show['copyright']; ?></p>

       <strong>Categories</strong>
       <p class="dont-break-out"><?php echo implode('<br>', explode(',', $show['categories'])); ?></p>

       <strong>Keywords</strong>
       <p class="dont-break-out"><?php echo implode(', ', explode(',', $show['keywords'])); ?></p>

       <strong>Author</strong>
       <p><?php echo $show['author']; ?></p>

       <strong>Editor</strong>
       <p><?php echo $show['managingEditor']; ?></p>

       <strong>Owner</strong>
       <p><?php echo $show['ownerName']; ?> (<?php echo $show['ownerEmail']; ?>)</p>

       <strong>Language</strong>
       <p><?php echo $show['language']; ?></p>
        
       <strong>Show Link</strong>
       <p><a href="<?php echo $hp->getShowOrEpisodeLink($show); ?>"><?php echo $hp->getShowOrEpisodeLink($show); ?></a></p>

    </div>
    <div class="col-sm-9">
        <h3 style="margin-top: 0;">Description</h3>
        <?php echo $show['description']; ?>

        <div class="row" style="margin-top: 2rem;">
            <div class="col-sm-6"><h3 style="margin-top: 0;">Episodes</h3></div>
            <div class="col-sm-6 text-right"><button class="btn btn-sm btn-primary" data-dialog="add-episode"><i class="fa fa-plus"></i> Publish New Episode</button></div>
        </div>
        <table class="table" style="font-size: .9rem; margin-top: .5rem; ">
            <tr>
                <th></th>
                <th>#</th>
                <th>Name</th>
                <th>Length</th>
                <th>Released</th>
                <th><span class="fa fa-download"></span></th>
                <th></th>
            </tr>
            <?php if ($episodes->numRows() > 0) { ?>
                <?php foreach ($episodes as $i => $episode) { ?>
                    <tr>
                        <td>
                          <a href="<?php echo $hp->getAudioFile($episode)->getUrl(); ?>" target="_blank"><span class="fa fa-play"></span></a></td>
                        <td><?php echo $i + 1; ?></td>
                        <td><div class="truncate" style="width: 300px;"><?php echo $episode['title']; ?></div></td>
                        <td><?php echo $episode['duration']; ?></td>
                        <td><?php echo (new DateTime($episode['pubDate']))->format('d M, Y'); ?></td>
                        <td><?php echo $st->getEpisodeDownloads($episode); ?></td>
                        <td>
                          <a href="<?php echo View::url('/dashboard/podster/edit_episode/' . $episode['id']); ?>"><span class="fa fa-edit"></span></a>
                          <a href="<?php echo View::url('/dashboard/podster/delete_episode/' . $episode['id']); ?>?ccm_token=<?php echo urlencode($this->controller->token->generate('delete_episode')); ?>" onclick="return confirm('Are you sure you want to delete \'<?php echo $episode['title']; ?>\'?');"><span class="fa fa-trash"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
            <tr>
                <td colspan="7">No episodes.</td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php include('_footer.php'); ?>
</div>