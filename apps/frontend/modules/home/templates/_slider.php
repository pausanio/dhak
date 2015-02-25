<?php $lang = $sf_user->getCulture(); ?>

<?php if ($slider->count() > 0): ?>
    <div id="cms_slider" class="row">
        <div class="span12">
            <ul class="bxslider">
                <?php foreach ($slider as $slide): ?>

                    <?php
                    if ($slide->getLayout() == 0) {
                        $class = 'pull-left';
                    } else if ($slide->getLayout() == 2) {
                        $class = 'pull-right';
                    } else {
                        $class = 'center';
                    }
                    ?>

                    <li class="layout_<?php echo $slide->getLayout() ?>">

                        <div class="media">
                            <?php if ($slide->getImageSrc()): ?>
                                <a class="<?php echo $class ?>" href="<?php echo url_for($slide->url); ?>">
                                    <img class="media-object" data-src="<?php echo $slide->getImageSrc(); ?>" src="<?php echo $slide->getImageSrc(); ?>" alt="<?php echo $slide->title; ?>" />
                                </a>
                            <?php endif ?>
                            <?php if ($slide->getLayout() != 1): ?>
                                <div class="media-body">
                                    <h1 class="media-heading"><?php echo $slide->title; ?></h1>
                                    <p><?php echo $slide->text; ?></p>
                                </div>
                            <?php endif ?>
                            <a href="<?php echo url_for($slide->url); ?>" class="btn btn-primary <?php echo $lang ?>">
                                <?php echo $slide->button_text; ?>
                            </a>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
