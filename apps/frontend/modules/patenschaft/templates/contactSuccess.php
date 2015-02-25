<?php $lang = $sf_user->getCulture() ?>
<?php if (isset($subnavi)) echo htmlspecialchars_decode($subnavi) ?>

<div class="cms-content">

    <?php echo htmlspecialchars_decode($cms_text['main']['de']) ?>

    <h2>Ich möchte eine Restaurierungspatenschaft übernehmen!</h2>
    <?php if (!$sendform) : ?>
        <p><strong>Bitte nehmen Sie mit mir Kontakt auf über</strong></p>

        <?php echo $form->renderFormTag(url_for('@patenschaft_kontakt'), array('id' => 'patenschaft_kontakt', 'class' => 'form-horizontal')) ?>
        <?php if ($form->hasErrors()): ?>
            <div class="formee-msg-error">
                <?php echo $form->renderGlobalErrors() ?>
                <p>Einige Eingaben konnten wir nicht verarbeiten. Bitte überprüfen Sie Ihre Angaben!</p>
            </div>
        <?php endif; ?>

        <fieldset>
            <legend>Kontaktdaten</legend>
            <div class="control-group">
                <label class="control-label required" for="contact_salutation">Name</label>
                <div class="controls controls-row">
                    <?php echo $form['salutation']->render(array('placeholder' => 'Anrede', 'class' => 'span2')) ?>
                    <?php echo $form['salutation']->renderError() ?>
                    <?php echo $form['firstname']->render(array('placeholder' => 'Vorname', 'class' => 'span3')) ?>
                    <?php echo $form['firstname']->renderError() ?>
                    <?php echo $form['lastname']->render(array('placeholder' => 'Nachname', 'class' => 'span3')) ?>
                    <?php echo $form['lastname']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['email']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['email']->render(array('placeholder' => 'E-Mail-Adresse', 'class' => 'span8')) ?>
                    <?php echo $form['email']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['institution']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['institution']->render(array('placeholder' => 'Firma / Institution', 'class' => 'span8')) ?>
                    <?php echo $form['institution']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label required" for="contact_street">Anschrift</label>
                <div class="controls controls-row">
                    <?php echo $form['street']->render(array('placeholder' => 'Straße', 'class' => 'span3')) ?>
                    <?php echo $form['street']->renderError() ?>
                    <?php echo $form['zip']->render(array('placeholder' => 'PLZ', 'class' => 'span1')) ?>
                    <?php echo $form['zip']->renderError() ?>
                    <?php echo $form['city']->render(array('placeholder' => 'Ort', 'class' => 'span2')) ?>
                    <?php echo $form['city']->renderError() ?>
                    <?php echo $form['country']->render(array('placeholder' => 'Land', 'class' => 'span2')) ?>
                    <?php echo $form['country']->renderError() ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Patenschaft</legend>
            <div class="control-group">
                <?php echo $form['subject']->renderLabel(null, array('class' => 'control-label required')) ?>
                <div class="controls controls-row">
                    <?php echo $form['subject']->render(array('placeholder' => 'Betreff', 'class' => 'span8')) ?>
                    <?php echo $form['subject']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['objects']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['objects']->render(array('placeholder' => 'Hier bitte die Signatur eingeben', 'class' => 'span8')) ?>
                    <?php echo $form['objects']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['address']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['address']->render(array('placeholder' => 'Anschrift, falls abweichend', 'class' => 'span8')) ?>
                    <?php echo $form['address']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['surplus']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['surplus']->render() ?>
                    <?php echo $form['surplus']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['receipt']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['receipt']->render() ?>
                    <?php echo $form['receipt']->renderError() ?>
                </div>
            </div>

        </fieldset>

        <fieldset>
            <legend>Veröffentlichung</legend>
            <div class="control-group">
                <?php echo $form['label']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['label']->render(array('placeholder' => 'Anschrift, falls abweichend')) ?>
                    <?php echo $form['label']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['public']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['public']->render(array('placeholder' => 'Anschrift, falls abweichend')) ?>
                    <?php echo $form['public']->renderError() ?>
                </div>
            </div>
            <div class="control-group">
                <?php echo $form['labelname']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['labelname']->render(array('placeholder' => 'Zum Beispiel "Max Mustermann, Mannheim', 'class' => 'span8')) ?>
                    <?php echo $form['labelname']->renderError() ?>
                </div>
            </div>
        </fieldset>

        <fieldset class="single-field">
            <legend>Weitere Angaben</legend>
            <div class="control-group">
                <?php echo $form['comment']->renderLabel(null, array('class' => 'control-label')) ?>
                <div class="controls controls-row">
                    <?php echo $form['comment']->render(array('placeholder' => '', 'class' => 'span8')) ?>
                    <?php echo $form['comment']->renderError() ?>
                </div>
            </div>
        </fieldset>

        <hr>

        <div class="control-group">
            <div class="controls pull-right">
                <input class="btn btn-primary" type="submit" value="<?php echo __('Absenden') ?>" />
            </div>
        </div>

        <p>
            <small>Mit einem Stern * gekennzeichnete Felder sind Pflichtfelder.</small>
        </p>

    </form>
<?php else: ?>
    <p><strong>Ihre Anfrage wurde erfolgreich versendet.</strong></p>
<?php endif; ?>

</div>
