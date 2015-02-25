<h1>
  <?php echo $bestand->getBestandsname() ?>
  <small>Bestand <?php echo $bestand->getBestandSig() ?></small>
</h1>

<?php include_partial('bestand/details', array('bestand' => $bestand)) ?>

<?php include_partial('bestand/verzeichnungseinheiten', array('verzeichnungseinheiten' => $verzeichnungseinheiten)) ?>

<?php include_partial('bestand/dokumente', array('dokumente' => $dokumente)) ?>
