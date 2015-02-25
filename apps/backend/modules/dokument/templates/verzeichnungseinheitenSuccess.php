<option></option>
<?php foreach($verzeichnungseinheiten as $verzeichnungseinheit): ?>
  <option value="<?php echo $verzeichnungseinheit->getId() ?>"><?php echo $verzeichnungseinheit->getTitel() ?></option>
<?php endforeach ?>
