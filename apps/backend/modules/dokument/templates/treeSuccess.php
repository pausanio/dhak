
  <?php if ($tree): ?>
    <?php include_partial('dokument/tree', array('tree' => $tree, 'path' => $path, 'current' => $current, 'parent' => null)) ?>
  <?php endif; ?>
