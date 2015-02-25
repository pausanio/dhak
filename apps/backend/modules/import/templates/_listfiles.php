
<div class="row">
    <div class="span12">
        <table class="table table-bordered table-striped table-condensed v-align">
            <colgroup>
                <col class="span1">
                <col class="span2">
                <col class="span1">
                <col class="span2">
                <col class="span2">
                <col class="span1">
                <col class="span1">
                <col class="span1">
            </colgroup>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>XML-Datei</th>
                    <th>Größe</th>
                    <th>Erstellungsdatum</th>
                    <th>Aktualisierungsdatum</th>
                    <th>Validierung</th>
                    <th>Import</th>
                    <th>Operationen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td>
                            <?php foreach ($file->status as $status): ?>
                                <?php echo DhastkLogFile::$map[$status]; ?><br />
                            <?php endforeach ?>
                        </td>
                        <td><?php echo pathinfo($file->file, PATHINFO_BASENAME) ?></td>
                        <td><?php echo $file->size ?></td>
                        <td><?php echo $file->created_at ?></td>
                        <td><?php echo $file->updated_at ?></td>
                        <td>
                            <?php if ($file->validation): ?>
                                <a class="btn btn-mini <?php if (in_array(DhastkLogFile::VALIDATEDFAIL, $file->status)): ?>btn-warning<?php endif ?>" target="_blank" href="<?php echo url_for('import_showlog', array('folder' => $type, 'type' => DhastkLogFile::VALIDATED, 'file' => pathinfo($file->file, PATHINFO_BASENAME))) ?>">Ergebnis</a>
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                        <td><?php if ($file->import): ?>
                                <a class="btn btn-mini <?php if (in_array(DhastkLogFile::IMPORTEDFAIL, $file->status)): ?>btn-warning<?php endif ?>" target="_blank" href="<?php echo url_for('import_showlog', array('folder' => $type, 'type' => DhastkLogFile::IMPORTED, 'file' => pathinfo($file->file, PATHINFO_BASENAME))) ?>">Ergebnis</a>
                            <?php else: ?>
                                -
                            <?php endif ?>
                        </td>
                        <td>
                            <a class="btn btn-mini" target="_blank" href="<?php echo url_for('import_showfile', array('folder' => $type, 'file' => pathinfo($file->file, PATHINFO_BASENAME))) ?>">Öffnen</a>
                            <?php /* <a class = "btn btn-mini btn-warning" href = "<?php echo url_for('import/' . $type . '?file=' . pathinfo($file->file, PATHINFO_BASENAME)) ?>">Erneut Importieren</a> */ ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>