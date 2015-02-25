<?php if (count($verzeichnungseinheiten) > 0): ?>
  <hr>
  <h3>
    Verzeichnungsheiten
    <small>Gesamt: <?php echo count($verzeichnungseinheiten) ?></small>
  </h3>
  <table class="table table-bordered table-striped v-align">
    <thead>
      <tr>
        <th>Signatur</th>
        <th>Archivgutart</th>
        <th>Titel</th>
        <th>Laufzeit</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($verzeichnungseinheiten as $verzeichnungseinheit): ?>
        <tr>
          <td>
            <a href="<?php echo url_for('verzeichnungseinheit/edit?id=' . $verzeichnungseinheit->getId()) ?>">
              <?php echo $verzeichnungseinheit->getSignatur(); ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('verzeichnungseinheit/edit?id=' . $verzeichnungseinheit->getId()) ?>">
              <?php echo $verzeichnungseinheit->getArchivgutart(); ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('verzeichnungseinheit/edit?id=' . $verzeichnungseinheit->getId()) ?>">
              <?php echo $verzeichnungseinheit->getTitel(); ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('verzeichnungseinheit/edit?id=' . $verzeichnungseinheit->getId()) ?>">
              <?php echo $verzeichnungseinheit->getLaufzeit(); ?>
            </a>
          </td>
          <td>
            <a href="<?php echo url_for('verzeichnungseinheit/edit?id=' . $verzeichnungseinheit->getId()) ?>">
              <?php if ($verzeichnungseinheit->getStatus() == 1): ?>
                <i class="icon icon-eye-open"></i>
              <?php else: ?>
                <i class="icon icon-eye-close"></i>
              <?php endif ?>
            </a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  <?php
 endif ?>