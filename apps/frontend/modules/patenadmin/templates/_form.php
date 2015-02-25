<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php echo $form->renderGlobalErrors() ?>

<form class="formee" action="<?php echo url_for('patenadmin/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php if ($form->hasErrors()) : ?>
    <div class="formee-msg-error">
      <p>Einige Eingaben konnten wir nicht verarbeiten. Bitte überprüfen Sie Ihre Angaben!</p>
    </div>
  <?php endif; ?>
  <table id="formtable">
    <tfoot>
      <tr>
        <td colspan="2" class="a-right">
          <a class="formee-button formee-button-alt" href="<?php echo url_for('patenadmin/index') ?>">Zurück</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Löschen', 'patenadmin/delete?id='.$form->getObject()->getId(), array('class' => 'formee-button','method' => 'delete', 'confirm' => 'Sind Sie sicher?')) ?>
            <input type="hidden" name="sf_method" value="put" />
          <?php endif; ?>
          <input type="submit" value="Speichern" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
