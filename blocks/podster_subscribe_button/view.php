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
        <span>The podlove is disabled in edit mode.</span>
    </div>
<?php } else { ?>
    <script>
    window.podsterSubscriber<?php echo $bID; ?> = {
        "title": "<?php echo $show['title']; ?>",
        "subtitle": "<?php echo $show['subTitle']; ?>",
        "description": "<?php echo preg_replace( "/\r|\n/", "", trim(strip_tags($show['description']))); ?>",
        "cover": "<?php echo $hp->getCoverUrl($show); ?>",
        "feeds": [
            {
                "type": "audio",
                "format": "mp3",
                "url": "<?php echo $hp->getShowFeedUrl($show); ?>",
                "variant": "high"
            },
        ],
    }
    </script>
    <script 
        class="podlove-subscribe-button" 
        src="//cdn.podlove.org/subscribe-button/javascripts/app.js" 
        data-language="<?php echo $show['language']; ?>" 
        data-size="<?php echo $btnSize; ?>" 
        data-format="<?php echo $btnFormat; ?>" 
        data-style="<?php echo $btnStyle; ?>" 
        data-color="<?php echo $btnColour; ?>" 
        data-json-data="podsterSubscriber<?php echo $bID; ?>"
    ></script>
<?php } ?>