<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="formee" action="<?php echo url_for('patenadmin/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php echo $form->renderGlobalErrors() ?>
  <?php if ($form->hasErrors()) : ?>
    <div class="this_errors">Einige Eingaben konnten wir nicht verarbeiten. Bitte überprüfen Sie die markierten Felder.</div>
  <?php endif; ?>
  <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>
  <table id="formtable">
    <thead>
      <tr>
        <td colspan="2"><strong>Abbildung hinzufügen</strong></td>
      </tr>
    </thead>
    <tbody>
      <?php echo $form ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="a-right">
          <input type="submit" value="Speichern" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
