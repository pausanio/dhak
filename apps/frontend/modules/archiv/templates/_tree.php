<?php if ($parent != null): ?>
    <ul class="navArchive">
    <?php endif ?>
    <?php foreach ($tree as $node): ?>
        <?php if ($node['status'] == 1): ?>
            <?php if ($parent != null): ?>
                <li class="<?php if ($node['id'] == $current): ?>active <?php endif ?> <?php if (substr($node['signatur'], 0, 2) == 'X-'): ?>x-level<?php endif ?>">
                    <?php include_partial('archiv/treenode', array('node' => $node, 'current' => $current)) ?>
                <?php endif ?>
                <?php if (count($node['__children']) > 0 && (in_array($node['id'], $path))): ?>
                    <?php include_partial('archiv/tree', array('tree' => $node['__children'], 'path' => $path, 'current' => $current, 'parent' => $node)) ?>
                <?php endif ?>
            </li>
            <?php if ($node['level'] == 1): ?>
                <li class="divider"></li>
                <?php endif ?>
            <?php endif ?>
        <?php endforeach ?>
        <?php if ($parent != null): ?>
    </ul>
<?php endif ?>

