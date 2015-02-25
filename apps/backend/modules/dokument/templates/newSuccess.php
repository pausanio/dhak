<h1>Neues Dokument anlegen</h1>
<div class="row">

    <div class="span5">
        <div class="well sidebar-nav">
            <div id="loadingTree"><span>Tektonik wird geladen...</span></div>
            <div id="archiveTree"></div>
        </div>
    </div>
    <div class="span7">
        <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="alert alert-success">
                <button class="close" data-dismiss="alert">×</button>
                <?php echo $sf_user->getFlash('notice') ?>
            </div>
        <?php endif ?>
        <?php if ($sf_user->hasFlash('error')): ?>
            <div class="alert alert-error">
                <button class="close" data-dismiss="alert">×</button>
                <?php echo $sf_user->getFlash('error') ?>
            </div>
        <?php endif ?>
        <div class="content archiv">
            <?php include_partial('form', array('form' => $form,
                'archiv_id' => $archiv_id,
                'archiv_title' => 'bitte links eine Tektonik auswählen',
                'verzeichnungseinheiten' => $verzeichnungseinheiten,
                'selected_ve' => false)) ?>
        </div>
    </div>
</div>