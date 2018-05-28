<?php
defined('C5_EXECUTE') or die('Access Denied.');

/*
 * This file is part of Podster.
 *
 * (c) Oliver Green <oliver@c5labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

?>
<!--<div id="blockBoilerplateForm">
    <span>Edit the blocks form.php to add input fields here.</span>

    <div class="form-group">
        <?php echo $form->label('my_field', t('My Text Field'))?>
        <?php echo $form->text('my_field', 1, $info['my_field']); ?>
    </div>
</div>!-->

<div class="form-group">
    <label for="showID"><?=t('Show')?></label>
    <?=$form->select('showID', [0 => 'Select Show...'] + $availableShows, $showID, array('class' => 'span8'))?>
</div>

<div class="form-group">
    <label for="episodeID"><?=t('Episode')?></label>
    <select name="episodeID" id="episodeID" class="form-control" data-default-episode="<?php echo $episodeID; ?>" disabled="disabled">
        <option value="0">Latest Episode</option>
    </select>
</div>

<script>
    var episodes = <?php echo json_encode($availableEpisodes, JSON_PRETTY_PRINT); ?>;

    $(function() {
        $('#showID').change(function() {
            var showID = $(this).val();
            var episodeID = $('#episodeID');
            var defaultEpisode = episodeID.data('default-episode');

            episodeID.empty();
            episodeID.append('<option value="0">Latest Episode</option>');

            for (var i = 0; i < episodes.length; i++) {
                if (episodes[i].showID === showID) {
                    var o = $('<option value="' + episodes[i].id + '">' + episodes[i].title + '</option>');
                    episodeID.append(o);

                    if (episodes[i].id === defaultEpisode.toString()) {
                        o.attr('selected', 'selected');
                    }
                }
            }

            if ('0' === showID) {
                episodeID.attr('disabled', 'disabled');
            } else {
                episodeID.removeAttr('disabled');
            }
        }).trigger('change');
    });    
</script>