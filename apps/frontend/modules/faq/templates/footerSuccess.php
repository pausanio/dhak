<?php $lang = $sf_user->getCulture() ?>

<?php
$active = 'faq';

$items = array(
    'news' => __('Blog'),
    'faq' => __('FAQ'),
    'team' => __('Das Team'),
    'links' => __('Links'),
    'legal' => __('Impressum'),
    'contact' => __('Kontakt')
);

$routes = array(
    'news' => 'news',
    'faq' => 'faq',
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
                <a title="<?php echo __('News Feed abonnieren') ?>" class="rss tiptool" target="_blank" href="<?php echo $sf_request->getUriPrefix() . $sf_request->getRelativeUrlRoot() . url_for('news', array('sf_format' => 'atom')) ?>">
                    <?php echo __('News Feed abonnieren') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo __('Newsletter-Anmeldung'); ?>" class="contact tiptool" href="<?php echo url_for('newsletter') ?>">
                    <?php echo __('Newsletter-Anmeldung'); ?>
                </a>
            </li>
            <li class="socialicon">
                <a title=" <?php echo __('Besuchen Sie uns bei Google+') ?>" class="googleplus tiptool" target="_blank" href="https://plus.google.com/116483449251277844482/posts?hl=de">
                    <?php echo __('Besuchen Sie uns bei Google+') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo __('Besuchen Sie uns bei Facebook') ?>" class="facebook tiptool" target="_blank" href="https://www.facebook.com/DigitalesHistorischesArchivKoeln">
                    <?php echo __('Besuchen Sie uns bei Facebook') ?>
                </a>
            </li>
            <li class="socialicon">
                <a title="<?php echo __('Folgen Sie uns bei Twitter') ?>" class="twitter tiptool" target="_blank" href="https://twitter.com/digarchivkoeln">
                    <?php echo __('Folgen Sie uns bei Twitter') ?>
                </a>
            </li>
        </ul>
        <div class="footer-content">

            <div class="row-fluid">
                <div class="span9">
                    <?php echo htmlspecialchars_decode($cms_text['intro']['de']); ?>
                    <div class="accordion" id="news_accordion">
                        <?php foreach ($ha_faqs as $i => $faq): ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_faq" href="#collapse_<?php echo $faq->getId() ?>">
                                        <?php echo htmlspecialchars_decode($faq->getQuestion()) ?>
                                    </a>
                                </div>
                                <div id="collapse_<?php echo $faq->getId() ?>" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <?php echo htmlspecialchars_decode($faq->getAnswer()) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="span3">
                    <img src="/images/footer_faq_fragemann.png" alt="FAQs - Frequently Asked Questions" />
                    <?php if ($sendform === false): ?>
                        <h2>Bleibt Ihre Frage offen?</h2>
                        <p>Füllen Sie bitte das Formular aus und wir melden uns schnellstmöglich bei Ihnen.</p>
                        <?php echo $form->renderFormTag('frequently-asked-questions') ?>
                        <?php echo $form['name']->renderRow() ?>
                        <?php echo $form['email']->renderRow() ?>
                        <?php echo $form['comment']->renderRow() ?>
                        <input type="hidden" id="mail_type" name="mail_type" value="faq" />
                        <input id="_mail_send" type="submit" class="btn btn-small btn-primary" value="Senden" />
                        </form>
                    <?php else: ?>
                        <p><strong>Vielen Dank!</strong></p>
                        <p>Ihre Anfrage wurde gesendet.</p>
                    <?php endif ?>
                </div>
            </div>
        </div>

    </div>
</div>

