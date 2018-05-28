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
<?php if (isset($invalid_configuration)) { ?>
    <div class="block-boilerplate">
        <span>Ooops! The show or episode cannot be shown.</span>
    </div>
<?php } elseif (Page::getCurrentPage()->isEditMode()) { ?>
    <div class="block-boilerplate">
        <span>The podcast player is disabled in edit mode.</span>
    </div>
<?php } else { ?>
    <script>
    window.podsterPlayer<?php echo $bID; ?>Config = {
        /*"extensions": {
            //"ChapterMarks": {},
            //"EpisodeInfo": {},
            //"Playlist": {},
            //"Transcript": {},
            //"SubscribeBar": {disabled: false}
        },*/
        "podcast": {
            "title": "<?php echo $show['title']; ?>",
            "feed": "<?php echo $hp->getShowFeedUrl($show); ?>",
            "episodes": [
            <?php foreach ($relatedEpisodes as $relatedEpisode) { ?>
                {
                    "media": {"mp3": "<?php echo $hp->getEpisodeDownloadUrl($relatedEpisode); ?>"},
                    "coverUrl": "<?php echo $hp->getCoverUrl($show); ?>",
                    "title": "<?php echo $relatedEpisode['title']; ?>",
                    "subtitle": "<?php echo $relatedEpisode['subTitle']; ?>",
                    "url": "<?php echo $hp->getShowOrEpisodeLink($relatedEpisode); ?>",
                    //"embedCode": "<script class=\"podigee-podcast-player\" src=\"https://cdn.podigee.com/podcast-player/javascripts/podigee-podcast-player.js\" data-configuration=\"https://example.com/episode-2.json\"><\/script>",
                    "description": "<?php echo preg_replace( "/\r|\n/", "", trim(strip_tags($relatedEpisode['description']))); ?>",
                },
            <?php } ?>
            ],
        },
        "episode": {
            "media": {"mp3": "<?php echo $hp->getEpisodeDownloadUrl($episode); ?>"},
            "coverUrl": "<?php echo $hp->getCoverUrl($show); ?>",
            "title": "<?php echo $episode['title']; ?>",
            "subtitle": "<?php echo $episode['subTitle']; ?>",
            "url": "<?php echo $hp->getShowOrEpisodeLink($episode); ?>",
            //"embedCode": "<script class=\"podigee-podcast-player\" src=\"https://cdn.podigee.com/podcast-player/javascripts/podigee-podcast-player.js\" data-configuration=\"https://example.com/episode-2.json\"><\/script>",
            "description": "<?php echo preg_replace( "/\r|\n/", "", trim(strip_tags($episode['description']))); ?>",
        }
    }
    </script>
    <script 
        id="podsterPlayer<?php echo $bID; ?>" 
        class="podigee-podcast-player" 
        src="//cdn.podigee.com/podcast-player/javascripts/podigee-podcast-player.js" 
        data-configuration="podsterPlayer<?php echo $bID; ?>Config"
    ></script>
<?php } ?>