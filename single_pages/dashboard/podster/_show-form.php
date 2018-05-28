<form method="post" class="form-stacked" style="padding-left: 0px;" action="<?=$view->action('save_show')?>">
    <?=$this->controller->token->output('save_show')?>
    <?=$form->hidden('showId', isset($show) ? $show['id'] : '')?>

    <div class="form-group">
        <label for="title"><?=t('Title')?></label>
        <?=$form->text('title', isset($show) ? $show['title'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="subTitle"><?=t('Sub Title')?></label>
        <?=$form->text('subTitle', isset($show) ? $show['subTitle'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group">
        <label for="description"><?=t('Description')?></label>
        <?=$form->textarea('description', isset($show) ? $show['description'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group ">
        <label for="author"><?=t('Author Name')?></label>
        <?=$form->text('author', isset($show) ? $show['author'] : '', array('class' => 'span8'))?>
    </div>

    <?php 
        $linkType = $show['linkType'];
        $linkUrl = $show['linkUrl'];
        $linkCID = $show['linkCID'];
        
        include('_link-type-selector.php'); 
    ?>

    <div class="form-group">
        <?php
        echo $form->label('ccm-b-image', t('Show Cover Image'));
        echo $al->image('ccm-b-image', 'coverFileID', t('Choose Image'), isset($show) ? $hp->getCoverFile($show) : null);
        ?> 
    </div>

    <div class="form-group show-advanced hidden">
        <label for="copyright"><?=t('Copyright')?></label>
        <?=$form->text('copyright', isset($show) ? $show['copyright'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="ownerName"><?=t('Owner Name')?></label>
        <?=$form->text('ownerName', isset($show) ? $show['ownerName'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="ownerEmail"><?=t('Owner Email')?></label>
        <?=$form->text('ownerEmail', isset($show) ? $show['ownerEmail'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="managingEditor"><?=t('Managing Editor')?></label>
        <?=$form->text('managingEditor', isset($show) ? $show['managingEditor'] : '', array('class' => 'span8'))?>
    </div>

    <div class="form-group">
        <label for="categories"><?=t('Categories')?></label>
        <?=$form->select('categories', $hp->getCategories(), isset($show) ? $show['categories'] : 'en-us', array('class' => 'span8'))?>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="keywords"><?=t('Keywords')?></label>
        <?=$form->textarea('keywords', isset($show) ? $show['keywords'] : '', array('class' => 'span8'))?>
        <p class="form-help" style="color: #999;">Comma separated please.</p>
    </div>

    <div class="form-group show-advanced hidden">
        <label for="language"><?=t('Language')?></label>
        <?=$form->select('language', $hp->getLanguages(), isset($show) ? $show['language'] : 'en-us', array('class' => 'span8'))?>
    </div>

    <a href="javascript::void()" class="toggleAdvancedFields" style="margin-bottom: 2rem; display: block;">
        <span class="fa fa-cog"></span> <span id="fieldToggleLabel">Show more options</span>
    </a>

    <script>
        $(function() {            
            // Wire the show / hide advanced fields.
            $('.toggleAdvancedFields').click(function() {
                if ($('.show-advanced').hasClass('hidden')) {
                    $('.show-advanced').removeClass('hidden');
                    $('#fieldToggleLabel').html('Hide some options');
                } else {
                    $('.show-advanced').addClass('hidden');
                    $('#fieldToggleLabel').html('Show more options');
                }
            });
        });

        // Initially show all the fields if requested to.
        if (window.podstartAllFieldsVisible) {
            $('.toggleAdvancedFields').remove();
            $('.show-advanced').removeClass('hidden')
        }
    </script>
</form>