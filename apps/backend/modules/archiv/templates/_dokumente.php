<?php if (count($dokuments) > 0): ?>
<hr>
  <h3>
    Dokumente
    <small>Gesamt: <?php echo count($dokuments) ?></small>
  </h3>
  <table class="table table-bordered table-striped v-align">
    <thead>
      <tr>
        <th>Titel</th>
        <th>Status</th>
        <th>Geprüft</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($dokuments as $dokument): ?>
        <tr>
          <td>
            <a href="<?php echo url_for('dokument/edit?id='.$dokument->getId()) ?>">
              <?php echo $dokument->getTitel(); ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('dokument/edit?id='.$dokument->getId()) ?>">
              <?php if ($dokument->getStatus() == 1): ?>
                <i class="icon icon-eye-open"></i>
              <?php else: ?>
                <i class="icon icon-eye-close"></i>
              <?php endif ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('dokument/edit?id='.$dokument->getId()) ?>">
              <?php if ($dokument->getValidated() == 1): ?>
                <i class="icon icon-thumbs-up"></i>
              <?php else: ?>
                <i class="icon icon-thumbs-down"></i>
              <?php endif ?>
            </a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php endif ?>