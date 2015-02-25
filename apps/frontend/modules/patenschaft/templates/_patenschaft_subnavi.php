<div class="row">
    <div class="span12">
        <ul id="navInfo" class="nav nav-pills">
            <li<?php echo ($active == 'intro') ? ' class="active"' : '' ?>>
                <a href="<?php echo url_for('patenschaft_intro') ?>">
                    <?php echo __('Einleitung'); ?>
                </a>
            </li>
            <li<?php echo ($active == 'patenschaft') ? ' class="active"' : '' ?>>
                <a href="<?php echo url_for('patenschaft') ?>">
                    <?php echo __('Restaurierungspatenschaften'); ?>
                </a>
            </li>
            <li<?php echo ($active == 'list') ? ' class="active"' : '' ?>>
                <a href="<?php echo url_for('patenschaft_patenliste') ?>">
                    <?php echo __('Patenliste'); ?>
                </a>
            </li>
            <li<?php echo ($active == 'contact') ? ' class="active"' : '' ?>>
                <a href="<?php echo url_for('patenschaft_kontakt') ?>">
                    <?php echo __('Kontakt'); ?>
                </a>
            </li>
        </ul>
    </div>
</div>
