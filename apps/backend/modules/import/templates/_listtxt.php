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
          <th>Validerte CSV-Datei</th>
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
              <a class="btn btn-mini" target="_blank" href="/import/<?php echo $type ?>/<?php echo $file['file'] ?>">Öffnen</a>
              <a class="btn btn-mini btn-primary" href="<?php echo url_for('import/' . $type . '?file=' . $file['file']) ?>">Importieren</a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>