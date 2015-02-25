<?php
if ($sf_request->getAttribute('content_in_footer')) {
    $icon_url = url_for(htmlspecialchars_decode($sf_user->getAttribute('footer_minimize_destination')));
} else {
    $icon_url = url_for('news');
}

$lang = $sf_user->getCulture();

$items = array(
    'news' => __('Blog'),
    'faq_footer' => __('FAQ'),
    'team' => __('Das Team'),
    'links' => __('Links'),
    'legal' => __('Impressum'),
    'contact' => __('Kontakt')
);

$routes = array(
    'news' => 'news',
    'faq_footer' => 'faq_footer',
    'links' => 'links',
    'team' => 'team',
    'legal' => 'impressum',
    'contact' => 'contact'
);
?>

<div class="row">
    <div class="span12">
        <ul class="nav nav-tabs" id="menu_footer">
            <?php foreach ($items as $item => $title): ?>
                <li class="<?php echo $item ?> <?php echo ($item === $active) ? ' active' : ''; ?>">
                    <a href="<?php if (isset($routes[$item])) echo url_for($routes[$item]); ?>">
                        <?php echo $title; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="socialicon">
                <a title="<?php echo cms_widget('tooltip', 'News Feed abonnieren') ?>" class="rss tiptool" target="_blank" href="<?php echo $sf_request->getUriPrefix() . $sf_request->getRelativeUrlRoot() . url_for('news', array('sf_format' => 'atom')) ?>">
                    <?php echo cms_widget('tooltip', 'News Feed abonnieren') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo cms_widget('tooltip', 'Newsletter-Anmeldung'); ?>" class="contact tiptool" href="<?php echo url_for('newsletter') ?>">
                    <?php echo cms_widget('tooltip', 'Newsletter-Anmeldung'); ?>
                </a>
            </li>
            <li class="socialicon">
                <a title=" <?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Youtube') ?>" class="youtube tiptool" target="_blank" href="http://www.youtube.com/channel/UChU7K535Pgx20-ChMuozlqA">
                    <?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Youtube') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title=" <?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Google+') ?>" class="googleplus tiptool" target="_blank" href="https://plus.google.com/116483449251277844482/posts?hl=de">
                    <?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Google+') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Facebook') ?>" class="facebook tiptool" target="_blank" href="https://www.facebook.com/DigitalesHistorischesArchivKoeln">
                    <?php echo cms_widget('tooltip', 'Besuchen Sie uns bei Facebook') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo cms_widget('tooltip', 'Folgen Sie uns bei Twitter') ?>" class="twitter tiptool" target="_blank" href="https://twitter.com/digarchivkoeln">
                    <?php echo cms_widget('tooltip', 'Folgen Sie uns bei Twitter') ?>
                </a>
            </li>
        </ul>
        <div class="footer-content">
            <?php echo htmlspecialchars_decode($footer_content); ?>
        </div>
    </div>
</div>
