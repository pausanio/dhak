<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="formee" action="<?php echo url_for('dokument/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
  <?php else: ?>
    <h1>Ihr persönlicher Beitrag zum kulturellen Erbe der Stadt Köln.</h1>
    <p>
      Laden Sie eigene Archivalien hoch und ordnen diese in der Tektonik
      ein.
    </p>
    <p>
      Erstellen Sie dazu im ersten Schritt eine Verzeichnungseinheit, der Sie
      im folgenden ihr digitalisiertes Bildmaterial hinzufügen können.
    </p>
    <p>
      <strong>Zurdnung der Archivsystematik:</strong><br>
      Falls Sie die korrekte Verzeichnungseinheit nicht kennen, wählen Sie bitte
      auf passender Ebene „Unterlagen ohne Zuordnung“ aus. Wir werden dann die
      Zuordnung für Sie vornehmen.
    </p>
    <hr>
  <?php endif; ?>

  <?php if ($form->hasErrors()): ?>
    <div class="formee-msg-error">
      <p>Bitte überprüfen Sie Ihre Eingaben!</p>
    </div>
  <?php endif; ?>

  <table>
    <tbody>
      <tr>
        <th class="archiv_title">
          <label>Archivsystematik</label>
        </th>
        <td class="archiv_title">
          <?php if (!empty($archiv_title)): ?>
            <?php echo $archiv_title ?>
          <?php else: ?>
            Bitte links eine Archivsystematik wählen...
          <?php endif ?>
        </td>
      </tr>
      <tr>
        <th class="verzeichnungseinheit_title">
          <label>Verzeichnungseinheit</label>
        </th>
        <td class="verzeichnungseinheit_title">
          <select id="verzeichnungseinheit_id">
            <?php if ($verzeichnungseinheiten): ?>
              <?php foreach ($verzeichnungseinheiten as $verzeichnungseinheit): ?>
                <option <?php if ($selected_ve == $verzeichnungseinheit): ?>selected="selected" <?php endif ?>value="<?php echo $verzeichnungseinheit->getId() ?>"><?php echo $verzeichnungseinheit->getTitel() ?></option>
              <?php endforeach ?>
            <?php endif ?>
          </select>
        </td>
      </tr>
      <?php echo $form ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="a-right">
          &nbsp;<a class="formee-button formee-button-alt" href="<?php echo url_for('dokument/index') ?>">Zurück</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Löschen', 'dokument/delete?id=' . $form->getObject()->getId(), array('class' => 'formee-button', 'method' => 'delete', 'confirm' => 'Sind Sie sicher?')) ?>
          <?php endif; ?>
          <input type="submit" value="Speichern" />
        </td>
      </tr>
    </tfoot>
  </table>

</form>
