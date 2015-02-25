<li <?php if ($sf_request->getParameter('module') == 'archiv'): ?> class="active" <?php endif ?>>
    <a href="<?php echo url_for('@lesesaal_root') ?>" class="<?php echo ($sf_request->getParameter('module') == 'archiv') ? 'active' : ''; ?>">
        <?php echo __('Lesesaal') ?>
    </a>
</li>
<?php
    $meinArchivRoute = url_for('@mein_archiv');
    $currentRoute = $sf_context->getInstance()->getRouting()->getCurrentRouteName();
?>
<li <?php if ($currentRoute == 'mein_archiv'): ?> class="active" <?php endif ?>>
    <a href="<?php echo $meinArchivRoute ?>">
        <?php echo __('Mein Archiv') ?>
    </a>
</li>
<li <?php if ($sf_request->getParameter('pagename') == 'forum'): ?> class="active" <?php endif ?>>
    <a href="/forum">
        <?php echo __('Forum') ?>
    </a>
</li>
<li <?php if ($sf_request->getParameter('routename') == 'identifizierung'): ?> class="active" <?php endif ?>>
    <a href="/de/info/identifizierung">
        <?php echo __('Identifizierung') ?>
    </a>
</li>
<li <?php if ($sf_request->getParameter('action') == 'patenschaft' || $sf_request->getParameter('module') == 'patenschaft'): ?> class="active" <?php endif ?>>
    <a href="<?php echo url_for('patenschaft_intro') ?>">
        <?php echo __('Patenschaften') ?>
    </a>
</li>
