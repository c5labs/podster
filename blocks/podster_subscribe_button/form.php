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
    <label for="btnSize"><?=t('Size')?></label>
    <?=$form->select('btnSize', ['big' => 'Big', 'medium' => 'Medium', 'small' => 'Small'], $btnSize, array('class' => 'span8'))?>
</div>

<div class="form-group">
    <label for="btnFormat"><?=t('Format')?></label>
    <?=$form->select('btnFormat', ['cover' => 'Cover', 'rectangle' => 'Rectangle', 'square' => 'Square'], $btnFormat, array('class' => 'span8'))?>
</div>

<div class="form-group">
    <label for="btnStyle"><?=t('Style')?></label>
    <?=$form->select('btnStyle', ['filled' => 'Filled', 'outline' => 'Outline', 'frameless' => 'Frameless'], $btnStyle, array('class' => 'span8'))?>
</div>

<div class="form-group">
    <label for="btnColour"><?=t('Colour')?></label>
    <?=Loader::helper('form/color')->output('btnColour', $btnColour, ['preferredFormat' => 'hex']); ?>
</div>