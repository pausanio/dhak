<?php if ($parent != null): ?>
    <ul class="nav nav-list">
    <?php endif ?>

    <?php foreach ($tree as $node): ?>
        <?php if ($parent != null): ?>
            <li <?php if ($node['id'] == $current): ?>class="selected" <?php endif ?>>
                <?php include_partial('archiv/treenode', array('node' => $node, 'current' => $current)) ?>
            <?php endif ?>
            <?php if (count($node['__children']) > 0 && (in_array($node['id'], $path))): ?>
                <?php include_partial('archiv/tree', array('tree' => $node['__children'], 'path' => $path, 'current' => $current, 'parent' => $node)) ?>
            <?php endif ?>
        </li>
        <?php if ($node['level'] == 1): ?>
            <li class="divider"></li>
            <?php endif ?>
        <?php endforeach ?>
        <?php if ($parent != null): ?>
    </ul>
    <?php
 endif ?>
