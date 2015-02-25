<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="form-horizontal projekte" action="<?php echo url_for('projekte/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

    <?php if ($form->hasErrors()): ?>
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Bitte überprüfen Sie Ihre Eingaben!
        </div>
    <?php endif; ?>

    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>

    <?php echo $form ?>

    <hr>

    <a class= "btn" href = "<?php echo url_for('projekte/index') ?>"><i class="icon-list"></i> Zurück zur Übersicht</a>
    <?php if (!$form->getObject()->isNew()): ?>
        &nbsp;<?php echo link_to('<i class="icon-trash icon-white"></i> Löschen', 'projekte/delete?id=' . $form->getObject()->getId(), array('class' => 'btn btn-danger', 'method' => 'delete', 'confirm' => 'Sind Sie sicher, dass das Projekt endgültig gelöscht werden soll?')) ?>
    <?php endif; ?>
    <input class="btn btn-primary" type="submit" value="Speichern" />
</form>