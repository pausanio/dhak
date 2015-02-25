<div class="row">
    <div class="span12">
        <table class="table table-bordered table-striped table-condensed v-align">
            <colgroup>
                <col class="span3">
                <col class="span2">
                <col class="span2">
                <col class="span2">
                <col class="span3">
            </colgroup>
            <thead>
                <tr>
                    <th>XML-Datei</th>
                    <th>Größe</th>
                    <th>Erstellungsdatum</th>
                    <th>Aktualisierungsdatum</th>
                    <th>Operationen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?php echo $file['file'] ?></td>
                        <td><?php echo $file['size'] ?></td>
                        <td><?php echo $file['created_at'] ?></td>
                        <td><?php echo $file['updated_at'] ?></td>
                        <td>
                            <a class="btn btn-mini" target="_blank" href="<?php echo url_for('import_showfile', array('folder' => $type, 'file' => $file['file'])) ?>">Öffnen</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>