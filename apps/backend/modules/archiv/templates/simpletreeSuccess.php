<h1>Archivstruktur <small>Gesamtübersicht</small></h1>

<?php foreach ($treeObject->fetchTree() as $node): ?>
  <?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $node['level']) . $node['signatur'] . ' ' . $node['name'] . ' - id: ' . $node['id'] . ' ' . '<br/>'; ?>
<?php endforeach ?>