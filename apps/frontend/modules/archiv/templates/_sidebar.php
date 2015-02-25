<a href="<?php echo url_for('@lesesaal_root') ?>" class="archiv-home">
    Übersicht
</a>
<?php if ($tree): ?>
    <?php include_partial('archiv/tree', array('tree' => $tree, 'path' => $path, 'current' => $current, 'parent' => null)) ?>
<?php endif; ?>
<a href="/lav/index.php" id="lav_link">
    Zivilstandsregister und Zweitschriften der Personenstandsregister<br>
    <small>1.543.967 Einträge</small>
</a>

