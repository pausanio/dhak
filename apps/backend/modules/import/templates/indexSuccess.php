<?php use_javascript('/javascripts/lib/dropzone.min.js') ?>

<h1>
    Import
    <small>XML- und CSV-Dateien aus ActaPro</small>
</h1>

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

<ul class="nav nav-tabs" id="tabs_import">
    <li class="active"><a href="#tab_bestaende" data-toggle="tab">Bestände</a></li>
    <li><a href="#tab_verzeichnungseinheiten" data-toggle="tab">Verzeichnungseinheiten</a></li>
    <li><a href="#tab_archiv" data-toggle="tab">Archiv</a></li>
</ul>

<div class="tab-content">

    <div class="tab-pane active" id="tab_bestaende">
        <h2>Bestände</h2>
        <h3>SAFT-Datei hochladen</h3>
        <form action="<?php echo url_for('import/upload?type=xml') ?>" class="dropzone" id="dropzone_xml"></form>
        <?php include_partial('import/listfiles', array('type' => 'bestand', 'files' => $files_bestaende)) ?>
    </div>

    <div class="tab-pane" id="tab_verzeichnungseinheiten">
        <h2>Verzeichnungseinheiten</h2>
        <h3>CSV-Datei hochladen</h3>
        <form action="<?php echo url_for('import/upload?type=csv') ?>" class="dropzone" id="dropzone_csv"></form>
        <?php include_partial('import/listfiles', array('type' => 'verzeichnungseinheit', 'files' => $files_verzeichnungseinheiten)) ?>
        <?php if (count($files_verzeichnungseinheiten) > 0): ?>
            <?php #include_partial('import/listcsv', array('type' => 'verzeichnungseinheit', 'files' => $files_verzeichnungseinheiten)) ?>
        <?php endif ?>
        <?php #if (count($logs_verzeichnungseinheiten) > 0): ?>
        <?php #include_partial('import/listlog', array('type' => 'verzeichnungseinheit', 'files' => $logs_verzeichnungseinheiten)) ?>
        <?php #endif ?>
        <?php #if (count($txt_verzeichnungseinheiten) > 0): ?>
        <?php #include_partial('import/listtxt', array('type' => 'verzeichnungseinheit', 'files' => $txt_verzeichnungseinheiten)) ?>
        <?php #endif ?>
    </div>

    <div class="tab-pane" id="tab_archiv">
        <h2>Archiv</h2>
        <?php if (count($files_archiv) > 0): ?>
            <?php include_partial('import/listxml', array('type' => 'archiv', 'files' => $files_archiv)) ?>
        <?php endif ?>
        <?php if (count($logs_archiv) > 0): ?>
            <?php include_partial('import/listlog', array('type' => 'archiv', 'files' => $logs_archiv)) ?>
        <?php endif ?>
    </div>
</div>

<script>
    !function($) {
        Dropzone.options.dropzoneXml = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 10, // MB
            parallelUploads: 1,
            acceptedMimeTypes: "text/xml"
        };
        Dropzone.options.dropzoneCsv = {
            paramName: "file",
            maxFilesize: 5,
            parallelUploads: 1,
            //acceptedMimeTypes: "text/csv"
            acceptedFiles: ".csv"
        };
    }(window.jQuery)
</script>
