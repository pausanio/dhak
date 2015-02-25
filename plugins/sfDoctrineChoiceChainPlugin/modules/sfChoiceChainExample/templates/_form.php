<style type="text/css">
    .star{
        color: red;
    }
</style>


<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('sfChoiceChainExample/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a href="<?php echo url_for('sfChoiceChainExample/index') ?>">Back to list</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'sfChoiceChainExample/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['location']->renderLabel() ?></th>
        <td>
          <?php echo $form['location']->renderError() ?>
          <?php echo $form['location'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['location2']->renderLabel() ?></th>
        <td>
          <?php echo $form['location2']->renderError() ?>
          <?php echo $form['location2'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['categories']->renderLabel() ?></th>
        <td>
          <?php echo $form['categories']->renderError() ?>

          <strong>Category<span class="star">*</span></strong>: <?php echo $form['categories']->renderCategory(); ?><br />
          <strong>Sub Category<span class="star">*</span></strong>: <?php echo $form['categories']->renderSubCategory(); ?><br />
          Sub Sub Category: <?php echo $form['categories']->renderSubSubCategory(); ?><br />
        </td>
      </tr>
    </tbody>
  </table>
</form>
