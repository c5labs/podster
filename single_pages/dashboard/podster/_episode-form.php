<form method="post" class="form-stacked" style="padding-left: 0px;" action="<?=$view->action('save_episode')?>">
    <?=$this->controller->token->output('save_episode')?>
    <?=$form->hidden('episodeID', isset($episode) ? $episode['id'] : '')?>
    <?=$form->hidden('showID', isset($episode) ? $episode['showID'] : $show['id'])?>

    <div class="form-group">
        <label for="title"><?=t('Title')?></label>
        <?=$form->text('title', isset($episode) ? $episode['title'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group episode-advanced hidden">
        <label for="subTitle"><?=t('Sub Title')?></label>
        <?=$form->text('subTitle', isset($episode) ? $episode['subTitle'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group">
        <label for="description"><?=t('Description')?></label>
        <?php
            $editor = Core::make('editor');
            echo $editor->outputStandardEditor('description', isset($episode) ? $episode['description'] : '');
        ?>
    </div>

    <div class="form-group">
        <?php
        echo $form->label('ccm-b-mp3', t('Episode Audio File'));
        echo $al->file('ccm-b-mp3', 'mp3FileID', t('Choose MP3 File'), isset($episode) ? $hp->getAudioFile($episode) : null);
        ?> 
    </div>

    <div class="form-group">
        <label for="duration"><?=t('Duration')?></label>
        <?=$form->text('duration', isset($episode) ? $episode['duration'] : '', array('class' => 'span8'))?>
    </div>

    <?php 
        $linkType = $episode['linkType'];
        $linkUrl = $episode['linkUrl'];
        $linkCID = $episode['linkCID'];
        
        include('_link-type-selector.php'); 
    ?>

    <div class="form-group episode-advanced hidden">
        <?php
        echo $form->label('ccm-b-image', t('Episode Cover Image'));
        echo $al->image('ccm-b-image', 'coverFileID', t('Choose Image'), isset($episode) ? $hp->getCoverFile($episode) : null);
        ?> 
    </div>

    <div class="form-group episode-advanced hidden">
        <?php
        echo $form->label('ccm-b-transcript', t('Episode Transcript'));
        echo $al->file('ccm-b-transcript', 'transcriptFileID', t('Choose Transcript File'), isset($episode) ? $hp->getTranscriptFile($episode) : null);
        ?> 
    </div>

    <div class="form-group episode-advanced hidden">
        <label for="categories"><?=t('Categories')?></label>
        <?=$form->select('categories', $hp->getCategories(), isset($show) ? $show['categories'] : 'en-us', array('class' => 'span8'))?>
    </div>

    <div class="form-group episode-advanced hidden">
        <label for="keywords"><?=t('Keywords')?></label>
        <?=$form->textarea('keywords', isset($episode) ? $episode['keywords'] : '', array('class' => 'span8'))?>
        <p class="form-help" style="color: #999;">Comma separated please.</p>
    </div>

    <div class="form-group episode-advanced hidden">
        <label for="explicit"><?=t('Explicit')?></label>
        <?=$form->select('explicit', ['Yes' => 'Yes', 'No' => 'No'], isset($episode) ? $episode['explicit'] : 'No', array('class' => 'span8'))?>
    </div>

    <a href="javascript::void()" class="toggleAdvancedFields" style="margin-bottom: 2rem; display: block;">
        <span class="fa fa-cog"></span> <span id="fieldToggleLabel">Show more options</span>
    </a>

    <script>
        $(function() {
            $('.toggleAdvancedFields').click(function() {
                if ($('.episode-advanced').hasClass('hidden')) {
                    $('.episode-advanced').removeClass('hidden');
                    $('#fieldToggleLabel').html('Hide some options');
                } else {
                    $('.episode-advanced').addClass('hidden');
                    $('#fieldToggleLabel').html('Show more options');
                }
            });
        });

        if (window.podstartAllFieldsVisible) {
            $('.toggleAdvancedFields').remove();
            $('.episode-advanced').removeClass('hidden')
        }
    </script>
</form>