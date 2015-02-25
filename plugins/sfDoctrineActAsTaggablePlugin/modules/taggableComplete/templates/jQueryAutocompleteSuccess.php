<?php $tags = array() ?>
<?php foreach ($tagSuggestions as $tagInfo): ?>
  <?php $tags[] = $tagInfo['suggested'] ?>
<?php endforeach ?>
<?php echo json_encode($tags) ?>
