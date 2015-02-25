<?php slot('title', sprintf('%s News', get_slot('title'))); ?>
<?php use_helper('Date'); ?>
<?php use_helper('Tags'); ?>
<?php $lang = $sf_user->getCulture(); ?>

<div class="row-fluid">
    <div class="span8">

        <?php if ($q): ?>
            <h2>Suchergebnisse für „<?php echo $q ?>“</h2>
        <?php endif ?>

        <div class="accordion" id="news_accordion">
            <?php $i = 0 ?>
            <?php foreach ($ha_news as $news): ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#news_accordion" href="#article<?php echo $news->getId(); ?>">
                            <?php if ((int) $news->getUpdatedAt() > 0) : ?><span><?php echo format_date($news->getUpdatedAt(), 'D'); ?></span><?php endif; ?>
                            <?php echo $news->getNewsTitle() ?>
                        </a>
                    </div>
                    <div id="article<?php echo $news->getId(); ?>" class="accordion-body collapse <?php if ( ($id == false && $i == 0) || $id == $news->getId() ): ?>in<?php endif ?>">
                        <div class="accordion-inner">
                            <?php if ($news->getImageSrc()): ?>
                                <img src="<?php echo $news->getImageSrc() ?>" alt="<?php echo $news->getNewsTitle() ?>" />
                            <?php endif ?>
                            <p><?php echo $news->getNewsText() ?></p>
                            <p class="meta">
                                <?php echo $news->getNewsEinsteller(); ?><br />
                                <?php if (count($news->getTags()) > 0) : ?>
                                    <?php echo __('Veröffentlicht in:'); ?>
                                    <?php $i = 1; ?>
                                    <?php foreach ($news->getTags() as $tag): ?>
                                        <a href="<?php echo url_for('news') ?>?tags=<?php echo $tag; ?>"><?php echo $tag; ?></a>
                                        <?php if ($i < count($news->getTags())): ?>
                                            &nbsp;|
                                        <?php endif ?>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php $i++ ?>
            <?php endforeach; ?>
        </div>

        <?php if ($pager->haveToPaginate()): ?>
            <div class="pagination pagination-small">
                <ul>
                    <li><a href="<?php echo url_for('news') ?>?page=1"><?php echo __('Erste Seite') ?></a></li>
                    <?php foreach ($pager->getLinks() as $page): ?>
                        <?php if ($page == $pager->getPage()): ?>
                            <li class="active">
                                <a href="#"><?php echo $page ?></a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo url_for('news') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <li><a href="<?php echo url_for('news') ?>?page=<?php echo $pager->getLastPage() ?>"><?php echo __('Letzte Seite') ?></a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <div class="span4">

        <form id="searchBlog" name="search" class="" action="<?php echo url_for('news') ?>" method="get">
            <div class="input-append">
                <input type="text" class="span12" name="q" placeholder="<?php echo ($q) ? $q : __('Blog durchsuchen'); ?>" name="form[query]" />
                <button type="submit" class="btn"><i class="icon-search"></i> Suchen</button>
            </div>
        </form>

        <h3>Kategorien</h3>
        <?php echo tag_cloud($categories, '@news?tags=%s'); ?>

        <h3>Blog Archiv</h3>
        <ul class="unstyled">
            <?php foreach ($archive_months as $month): ?>
                <li><a href="<?php echo url_for('news') ?>?archive=<?php echo $month->getAmonth(); ?>"><?php echo format_date($month->getAmonth(), 'MMMM yyyy'); ?></a></li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>