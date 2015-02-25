<?php
/**
 * Render node types
 */
switch ($node['type']) {
    case 1: // tektonik
        ?><a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($node['type']) . '&id=' . $node['id']. '&slug='.str_replace('/','-',$node['signatur']).'+'.$node['name']) ?>" class="tektonik <?php if ($node['id'] == $current): ?>active<?php endif ?>">
            <b><?php echo $node['signatur'] . ' ' . $node['name'] ?></b><br>
            <small><?php echo number_format($node['count_ve'], 0, '.', '.') ?> Verzeichnungseinheiten | <?php echo number_format($node['count_docs'] + $node['count_userdocs'], 0, '.', '.') ?> Einträge</small></a><?php
        break;
    case 2: // bestand
        ?><a <?php if (substr($node['signatur'], 0, 2) == 'X-'): ?>title="<?php echo cms_widget('lesesaal', 'navigation_x_best') ?>"<?php endif ?> href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($node['type']) . '&id=' . $node['id']. '&slug='.str_replace('/','-',$node['signatur']).'+'.$node['name']) ?>" class="bestand <?php if ($node['id'] == $current): ?>active<?php endif ?>">
            <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
            <small><?php echo number_format($node['count_ve'], 0, '.', '.') ?> Verzeichnungseinheiten | <?php echo number_format($node['count_docs'] + $node['count_userdocs'], 0, '.', '.') ?> Einträge</small>
        </a><?php
        break;
    case 3: // klassifikation
        ?><a href="<?php echo url_for('@lesesaal?type=' .
        Archiv::getTypeSlug($node['type']) . '&id=' . $node['id'].'&slug='.str_replace('/','-',$node['signatur']).'+'.$node['name']) ?>" class="klassifikation <?php if ($node['id'] == $current): ?>active<?php endif ?>">
            <b><?php echo $node['name'] ?></b><br>
            <small><?php echo number_format($node['count_ve'], 0, '.', '.') ?> Verzeichnungseinheiten | <?php echo number_format($node['count_docs'] + $node['count_userdocs'], 0, '.', '.') ?> Einträge</small>
        </a><?php
        break;
    case 4: // bandserie
        ?><a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($node['type']) . '&id=' . $node['id']. '&slug='.str_replace('/','-',$node['signatur']).'+'.$node['name']) ?>" class="bandserie <?php if ($node['id'] == $current): ?>active<?php endif ?>">
            <b><?php echo $node['name'] ?></b><br>
            <small><?php echo number_format($node['count_ve'], 0, '.', '.') ?> Verzeichnungseinheiten | <?php echo number_format($node['count_docs'] + $node['count_userdocs'], 0, '.', '.') ?> Einträge</small>
        </a><?php
        break;
    default:
        ?><a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($node['type']) . '&id=' . $node['id']. '&slug='.str_replace('/','-',$node['signatur']).'+'.$node['name']) ?>">
            <b><?php echo $node['name'] ?></b> (<?php echo $node['signatur'] ?>)<br>
            <small><?php echo number_format($node['count_ve'], 0, '.', '.') ?> Verzeichnungseinheiten | <?php echo number_format($node['count_docs'] + $node['count_userdocs'], 0, '.', '.') ?> Einträge</small>
        </a><?php
        break;
}
?>
