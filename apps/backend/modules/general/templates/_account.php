<?php use_helper('I18N') ?>
<ul class="nav pull-right">
    <li>
        <a><i class="icon-user icon-white"></i>
            <?php echo $sf_user->getGuardUser()->getFirstName() ?>
            <?php echo $sf_user->getGuardUser()->getLastName() ?>
        </a>
    </li>
    <li class="divider-vertical"></li>
    <li><?php echo link_to('<i class="icon-off icon-white"></i> Abmelden', 'sf_guard_signout') ?></li>
</ul>