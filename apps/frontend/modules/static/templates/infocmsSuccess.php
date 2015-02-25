<?php slot('title', sprintf('%s %s', get_slot('title'), __('Informationen'))) ?>
<?php $lang = $sf_user->getCulture() ?>

<?php include_partial('static/infocms_navigation') ?>

<div class="cms-content">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo $page->getTitle(); ?></h1>
            <?php echo $page->getText(); ?>
        </div>
    </div>
</div>