<?php $lang = $sf_user->getCulture() ?>

<div class="col-left">
  <p><?php echo htmlspecialchars_decode($cms_text['intro']['de']); ?></p>
  <div class="faq">
    <?php foreach ($ha_faqs as $i => $ha_faq): ?>
      <div class="question">
        <h3>
          <span class="icons">&nbsp;</span>
          <span class="q"><?php echo htmlspecialchars_decode($ha_faq->getQuestion()) ?></span>
        </h3>
        <div class="answer">
          <p><?php echo htmlspecialchars_decode($ha_faq->getAnswer()) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<div class="col-right">
  <div class="a-right"><img src="/images/footer_faq_fragemann.png" alt="Fragemann" /></div>
  <?php if ($sendform === false): ?>
    <h2>Bleibt Ihre Frage offen?</h2>
    <p>Füllen Sie bitte das Formular aus und wir melden uns schnellstmöglich bei Ihnen.</p>
    <?php echo $form->renderFormTag('faq', array('class' => 'formee footer')) ?>
    <p>
      <?php echo $form['name']->renderLabel(); ?>
      <?php echo $form['name']->render(array('class' => 'text'), 'Ihr Name') ?>
      <?php echo $form['name']->renderError() ?>
    </p>
    <p>
      <?php echo $form['email']->renderLabel(); ?>
      <?php echo $form['email']->render(array('class' => 'text'), 'Ihre Email') ?>
      <?php echo $form['email']->renderError() ?>
    </p>
    <p>
      <?php echo $form['comment']->renderLabel(); ?>
      <?php echo $form['comment']->render(array('class' => 'comment'), 'Ihre Frage') ?>
      <?php echo $form['comment']->renderError() ?>
    </p>
    <p class="a-right">
      <input type="hidden" id="mail_type" name="mail_type" value="faq" />
      <input id="_mail_send" type="submit" class="submit" value="Senden" />
    </p>
  </form>
<?php else: ?>
  <p><strong>Vielen Dank!</strong></p>
  <p>Ihre Anfrage wurde gesendet.</p>
<?php endif ?>
</div>
<div class="fixfloat"></div>
