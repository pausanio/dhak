<?php use_helper('Date') ?>
<div class="row-fluid">
    <?php foreach ($ha_news as $i => $news): ?>
        <?php $newsdate = $news->getUpdatedAt() ?>
        <?php if ($i === 0) : ?>
            <div class="span7">


                <div class="media">
                    <a class="pull-left" href="<?php echo url_for('news', array('id' => $news->getId())); ?>#article<?php echo $news->getId(); ?>">
                        <img class="media-object img-rounded" src="<?php echo $news->getThumbSrc() ?>">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <a href="<?php echo url_for('news', array('id' => $news->getId())); ?>#article<?php echo $news->getId(); ?>">
                                <span><?php echo format_date($newsdate, 'D'); ?></span>
                                <?php echo htmlspecialchars_decode($news->getNewsTitle()) ?>
                            </a>
                        </h4>
                        <?php
                        if (strlen($news->getNewsTitle()) < 50) {
                            $teaser_lenght = 150;
                        } else {
                            $teaser_lenght = 70;
                        }
                        ?>
                        <?php echo substr(htmlspecialchars_decode($news->getNewsText()), 0, $teaser_lenght); ?>
                        ...(<a href="<?php echo url_for('news', array('id' => $news->getId())); ?>#article<?php echo $news->getId(); ?>"><?php echo __('mehr'); ?></a>)
                        </a>
                    </div>
                </div>

            </div>

            <div class="span5">
                <dl class="dl-horizontal">
                <?php else: ?>
                    <dt>
                    <a href="<?php echo url_for('news', array('id' => $news->getId())); ?>#article<?php echo $news->getId(); ?>" title="<?php echo strip_tags($news->getNewsTitle()); ?>">
                        <?php echo format_date($newsdate, 'dd.M.yyyy'); ?>
                    </a>
                    </dt>
                    <dd>
                        <a href="<?php echo url_for('news', array('id' => $news->getId())); ?>#article<?php echo $news->getId(); ?>" title="<?php echo strip_tags($news->getNewsTitle()); ?>">
                            <?php echo substr(htmlspecialchars_decode($news->getNewsTitle()), 0, 40) . ' ...'; ?>
                        </a>
                    </dd>
                <?php endif; ?>
            <?php endforeach; ?>
        </dl>
    </div>
</div>