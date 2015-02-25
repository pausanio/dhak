<?php slot('title', sprintf('%s %s', get_slot('title'), __('Links'))); ?>
<?php $lang = $sf_user->getCulture(); ?>

<div class="row-fluid">
    <div class="span6">
        <h2><?php echo __('Institutionen'); ?></h2>
        <?php
        if ($lang === 'en' AND $cms_text['left']['en'] !== '') {
            echo htmlspecialchars_decode($cms_text['left']['en']);
        } else {
            echo htmlspecialchars_decode($cms_text['left']['de']);
        }
        ?>
    </div>

    <div class="span6">
        <h2><?php echo __('Quellen und Literatur'); ?></h2>
        <?php
        if ($lang === 'en' AND $cms_text['right']['en'] !== '') {
            echo htmlspecialchars_decode($cms_text['right']['en']);
        } else {
            echo htmlspecialchars_decode($cms_text['right']['de']);
        }
        ?>
    </div>
</div>