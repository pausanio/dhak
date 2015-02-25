<hr>
<h2>Bestand</h2>
<table class="table table-bordered table-striped v-align">
  <tbody>
    <tr>
      <th>Name</th>
      <td><?php echo $bestand->getBestandsname(); ?></td>
    </tr>
    <tr>
      <th>Signatur</th>
      <td><?php echo $bestand->getBestandSig(); ?></td>
    </tr>
    <tr>
      <th>Laufzeit</th>
      <td><?php echo $bestand->getLaufzeit(); ?></td>
    </tr>
    <tr>
      <th>Inhalt</th>
      <td><?php echo $bestand->getBestandInhalt(); ?></td>
    </tr>
    <tr>
      <th>Umfang</th>
      <td><?php echo $bestand->getUmfang(); ?></td>
    </tr>
    <tr>
      <th>Bemerkung</th>
      <td><?php echo $bestand->getBem(); ?></td>
    </tr>
    <tr>
      <th>Sperrvermerk</th>
      <td><?php echo $bestand->getSperrvermerk(); ?></td>
    </tr>
    <tr>
      <th>Abg-Stelle</th>
      <td><?php echo $bestand->getAbgStelle(); ?></td>
    </tr>
    <tr>
      <th>Rechtsstatus</th>
      <td><?php echo $bestand->getRechtsstatus(); ?></td>
    </tr>
    <tr>
      <th>Status</th>
      <td>
        <?php if ($bestand->getStatus() == 1): ?>
          <i class="icon icon-eye-open"></i> Aktiv
        <?php else: ?>
          <i class="icon icon-eye-close"></i> Inaktiv
        <?php endif ?>
      </td>
    </tr>
  </tbody>
</table>
  <div class="btn-group">
    <a class="btn" href="<?php echo url_for('bestand/edit?id=' . $bestand->getId()) ?>"><i class="icon icon-edit"></i> Bearbeiten</a>
  </div>