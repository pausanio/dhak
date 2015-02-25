<?php
/**
 * Render node types
 * 1 = Tektonik
 * 2 = Bestand
 * 3 = Klassifikation
 * 4 = Bandserie
 */
switch ($node['type']) {
  case 1:
    ?><a href="#" data-id="<?php echo $node['id'] ?>" class="link <?php if ($node['id'] == $current): ?> active<?php endif ?>">
      <b><?php echo $node['signatur'] . ' ' . $node['name'] ?></b><br>
      <small><?php echo $node['count_ve'] ?> Verzeichnungseinheiten | <?php echo $node['count_docs'] ?> Einträge</small></a><?php
    break;
  case 2:
    ?><a href="#" data-id="<?php echo $node['id'] ?>" class="link <?php if ($node['id'] == $current): ?> active<?php endif ?>">
      <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
      <small><?php echo $node['count_ve'] ?> Verzeichnungseinheiten | <?php echo $node['count_docs'] ?> Einträge</small>
    </a><?php
    break;
  case 3:
    ?><a href="#" data-id="<?php echo $node['id'] ?>" class="link <?php if ($node['id'] == $current): ?> active<?php endif ?>">
      <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
      <small><?php echo $node['count_ve'] ?> Verzeichnungseinheiten | <?php echo $node['count_docs'] ?> Einträge</small>
    </a><?php
    break;
  case 4:
    ?><a href="#" data-id="<?php echo $node['id'] ?>" class="link <?php if ($node['id'] == $current): ?> active<?php endif ?>">
      <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
      <small><?php echo $node['count_ve'] ?> Verzeichnungseinheiten | <?php echo $node['count_docs'] ?> Einträge</small>
    </a><?php
    break;
  default:
      ?><a href="#" data-id="<?php echo $node['id'] ?>" class="link <?php if ($node['id'] == $current): ?> active<?php endif ?>">
        <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
        <small><?php echo $node['count_ve'] ?> Verzeichnungseinheiten | <?php echo $node['count_docs'] ?> Einträge</small>
    </a><?php
    break;
}
?>