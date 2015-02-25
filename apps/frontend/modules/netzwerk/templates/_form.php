<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('netzwerk/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('netzwerk/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'netzwerk/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['user_id']->renderLabel() ?></th>
        <td>
          <?php echo $form['user_id']->renderError() ?>
          <?php echo $form['user_id'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_title_de']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_title_de']->renderError() ?>
          <?php echo $form['projekt_title_de'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_title_en']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_title_en']->renderError() ?>
          <?php echo $form['projekt_title_en'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_type']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_type']->renderError() ?>
          <?php echo $form['projekt_type'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_einsteller']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_einsteller']->renderError() ?>
          <?php echo $form['projekt_einsteller'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_bestand']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_bestand']->renderError() ?>
          <?php echo $form['projekt_bestand'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['projekt_notiz']->renderLabel() ?></th>
        <td>
          <?php echo $form['projekt_notiz']->renderError() ?>
          <?php echo $form['projekt_notiz'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $form['status']->renderError() ?>
          <?php echo $form['status'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
