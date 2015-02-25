<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php echo $form->renderGlobalErrors() ?>

<?php echo form_tag_for($form, '@documents', array('class' => 'formee')) ?>

  <div class="formee-msg-info">
    <p>
      Gemäß des Kooperationsvertrags zwischen den Betreibern dieses Archivs und der
      Stadt Köln ist das Einstellen der Kopien und Digitalisate aus dem Stadtarchiv
      <strong>keine Publikation</strong> im Sinne der Entgeltordnung des Historischen Archivs der
      Stadt Köln.
    </p>
  </div>

  <?php if ($form->hasErrors()): ?>
    <div class="formee-msg-error">
      <p>Einige Eingaben konnten wir nicht verarbeiten. Bitte überprüfen Sie Ihre Angaben!</p>
    </div>
  <?php endif; ?>

  <table id="formtable">
    <tbody>
      <tr>
        <td>
          <fieldset>
            <legend><?php echo __('Archivsystematik'); ?></legend>
            <table>
              <tr>
                <th>
                  <label>Zuordnung zur Archivsystematik</label>
                  <p>
                    Falls Sie die korrekte Verzeichniseinheit nicht kennen,
                    wählen Sie bitte unter Tektonik „Unbekannt/noch nicht zugeordnet“ aus.
                    Wir werden dann die Zuordnung für Sie vornehmen.
                  </p>
                </th>
                <td>
                  <div class="signatur_chain">
                    <?php if ($form['signatur_chain']->hasError()) : ?>
                      <?php echo $form['signatur_chain']->renderError() ?>
                    <?php endif; ?>
                    <?php echo $form['signatur_chain']->render() ?>
                  </div>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend><?php echo __('Weitere Angaben'); ?></legend>
            <table>
              <tr>
                <th>
                  <label for="ha_documents_title_de"><?php echo __('Titel') ?>*</label>
                </th>
                <td>
                  <?php if ($form['title_de']->hasError()) : ?>
                    <?php echo $form['title_de']->renderError() ?>
                  <?php endif; ?>
                  <?php echo $form['title_de']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_datierung"><?php echo __('Datierung (Freitext)') ?></label>
                </th>
                <td>
                  <?php echo $form['datierung']->renderError() ?>
                  <?php echo $form['datierung']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_date_day"><?php echo __('Datierung') ?></label>
                </th>
                <td>
                  <p>
                    <label for="ha_documents_date_day">Tag:</label>
                    <?php echo $form['date_day']->render() ?>
                  </p>
                  <p>
                    <label for="ha_documents_date_month">Monat:</label>
                    <?php echo $form['date_month']->render() ?>
                  </p>
                  <p>
                    <label for="ha_documents_date_year">Jahr:</label>
                    <?php echo $form['date_year']->render() ?>
                  </p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php echo $form['date_day']->renderError() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_descr_de"><?php echo __('Beschreibung, Zusatzinformation') ?></label>
                </th>
                <td>
                  <?php echo $form['descr_de']->renderError() ?>
                  <?php echo $form['descr_de']->render() ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend><?php echo __('Vorlage'); ?></legend>
            <table>
              <tr>
                <th>
                  <label for="ha_documents_vorlage_id"><?php echo __('Liegt vor als') ?></label>
                </th>
                <td>
                  <?php if ($form['vorlage_id']->hasError()) : ?>
                    <?php echo $form['vorlage_id']->renderError() ?>
                  <?php endif; ?>
                  <?php echo $form['vorlage_id']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_link">
                    <?php echo __('Link (URL)') ?>
                    <span class="help">(wenn Online-Resource)</span>
                  </label>
                </th>
                <td>
                  <?php echo $form['link']->renderError() ?>
                  <?php echo $form['link']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_linkname"><?php echo __('Kurze Bezeichnung für den Link') ?></label>
                </th>
                <td>
                  <?php echo $form['linkname']->renderError() ?>
                  <?php echo $form['linkname']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_vorlage_comment"><?php echo __('Kommentar zur Vorlage') ?></label>
                </th>
                <td>
                  <?php echo $form['vorlage_comment']->renderError() ?>
                  <?php echo $form['vorlage_comment']->render() ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend><?php echo __('Digitale Datei hochladen'); ?></legend>
            <table>
              <tr>
                <th>
                  <label for="ha_documents_orgname"><?php echo __('Datei auswählen') ?></label>
                </th>
                <td>
                  <?php echo $form['orgname']->renderError() ?>
                  <?php echo $form['orgname']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_bildvorlage"><?php echo __('Rechtlicher Hinweis') ?></label>
                </th>
                <td>
                  <?php echo $form['bildvorlage']->renderError() ?>
                  <?php echo $form['bildvorlage']->render() ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset>
            <legend><?php echo __('Anzeige'); ?></legend>
            <table>
              <tr>
                <th>
                  <label for="ha_documents_autor"><?php echo __('Eingestellt von') ?></label>
                </th>
                <td>
                  <?php echo $form['autor']->renderError() ?>
                  <?php echo $form['autor']->render() ?>
                </td>
              </tr>
              <tr>
                <th>
                  <label for="ha_documents_status"><?php echo __('Anzeige') ?></label>
                </th>
                <td>
                  <?php echo $form['status']->renderError() ?>
                  <?php echo $form['status']->render() ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="a-right no-margin">
          <a class="formee-button formee-button-alt" href="<?php echo url_for('documents/index') ?>">Zurück</a>
          <input type="submit" value="Speichern" />
          <?php if ($form->isCSRFProtected()) echo $form['_csrf_token']->render(); ?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>