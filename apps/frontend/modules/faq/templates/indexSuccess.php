<?php $lang = $sf_user->getCulture() ?>
<div class="cms-content">
    <div class="row-fluid">
        <div class="span9">
            <?php echo htmlspecialchars_decode($cms_text['intro']['de']); ?>
            <div class="accordion" id="accordion_faq">
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
                <?php echo $form->renderFormTag('faq') ?>
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