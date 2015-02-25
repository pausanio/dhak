<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php echo $form->renderGlobalErrors() ?>
<style>
.this_errors{background:white;border:2px solid #C00; padding:8px;}
.this_error{color:#C00;background:transparent;margin-top:2px; padding:3px;width:220px;height:11px;}
</style>
<?php if ($form->hasErrors()) : ?>
<div class="this_errors">Einige Eingaben konnten wir nicht verarbeiten. Bitte überprüfen Sie die markierten Felder.</div>
<?php endif; ?>
<?php echo form_tag_for($form, '@document') ?>

  <table id="formtable">
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Speichern" />
        </td>
      </tr>
    </tfoot>
    <tbody><tr><td>
    <fieldset>
    <legend><?php echo __('Archivsystematik'); ?></legend>
    <table>
      <tr>
        <td colspan="2">
          Zuordnung zur Archivsystematik<br/><br/>
          Falls Sie die korrekte Verzeichnugseinheit hier nicht auswählen können, wählen Sie bitte unter Archiv <br />"Unbekannt/noch nicht zugeordnet" aus. <br/>Wir werden dann die Zuordnung vornehmen
          <br/><br/>
          <?php echo $form['signatur_chain']->render() ?>
          <?php if($form['signatur_chain']->hasError()) :?> <div class="this_error"><?php echo $form['signatur_chain']->renderError() ?></div><?php endif; ?>
        </td>
      </tr>
    </table>
    </fieldset>
    
    <fieldset>
    <legend><?php echo __('weitere Angaben'); ?></legend>
    <table>
      <tr>
        <th><label for="ha_documents_title_de"><?php echo __('Titel') ?>*</label></th>
        <td><?php echo $form['title_de']->render() ?>
           <?php if($form['title_de']->hasError()) :?> <div class="this_error"><?php echo $form['title_de']->renderError() ?></div><?php endif; ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_datierung"><?php echo __('Datierung (Freitext)') ?></label></th>
        <td><?php echo $form['datierung']->render() ?>
            <?php echo $form['datierung']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_date_day"><?php echo __('Datierung') ?></label></th>
        <td>Tag: <?php echo $form['date_day']->render() ?> Monat: <?php echo $form['date_month']->render()?> Jahr: <?php echo $form['date_year']->render() ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
            <?php echo $form['date_day']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_descr_de"><?php echo __('Beschreibung, Zusatzinformation') ?></label></th>
        <td><?php echo $form['descr_de']->render() ?>
            <?php echo $form['descr_de']->renderError() ?>
        </td>
      </tr>
    </table>
    </fieldset>
      
    <fieldset>
    <legend><?php echo __('Vorlage'); ?></legend>
    <table>
      <tr>
        <th><label for="ha_documents_vorlage_id"><?php echo __('liegt vor als') ?></label></th>
        <td><?php echo $form['vorlage_id']->render() ?>
            <?php if($form['vorlage_id']->hasError()) :?> <div class="this_error"><?php echo $form['vorlage_id']->renderError() ?></div><?php endif; ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_link"><?php echo __('Link (URL)') ?></label><br /><small>(wenn Online-Resource)</small></th>
        <td><?php echo $form['link']->render() ?>
            <?php echo $form['link']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_linkname"><?php echo __('Kurze Bezeichnung für den Link') ?></label></th>
        <td><?php echo $form['linkname']->render() ?>
            <?php echo $form['linkname']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_vorlage_comment"><?php echo __('Kommentar zur Vorlage') ?></label></th>
        <td><?php echo $form['vorlage_comment']->render() ?>
            <?php echo $form['vorlage_comment']->renderError() ?>
        </td>
      </tr>
    </table>
    </fieldset>
      
    <fieldset>
    <legend><?php echo __('Digitale Datei hochladen'); ?></legend>
    <table>
      <tr>
        <th><label for="ha_documents_orgname"><?php echo __('Datei auswählen') ?></label></th>
        <td><?php echo $form['orgname']->render() ?>
            <?php echo $form['orgname']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_bildvorlage"><?php echo __('Rechtlicher Hinweis') ?></label></th>
        <td><?php echo $form['bildvorlage']->render() ?>
            <?php echo $form['bildvorlage']->renderError() ?>
        </td>
      </tr>
    </table>
    </fieldset>
      
    <fieldset>
    <legend><?php echo __('Anzeige'); ?></legend>
    <table>
      <tr>
        <th><label for="ha_documents_autor"><?php echo __('eingestellt von') ?></label></th>
        <td><?php echo $form['autor']->render() ?>
            <?php echo $form['autor']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th><label for="ha_documents_status"><?php echo __('Anzeige') ?></label></th>
        <td><?php echo $form['status']->render() ?>
            <?php echo $form['status']->renderError() ?>
        </td>
      </tr>
    </table>
    </fieldset>
      
      <?php if ($form->isCSRFProtected()) echo $form['_csrf_token']->render();?>
    </td></tr></tbody>
  </table>
</form>