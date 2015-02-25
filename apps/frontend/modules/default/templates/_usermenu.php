<ul class="nav pull-right">
    <li class="dropdown">
        <?php if ($sf_user->isAuthenticated()): ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <small>
                    <i class="icon-user"></i> <?php echo $sf_user->getUsername() ?>
                    <b class="caret"></b>
                </small>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo url_for('@sf_guard_profile') ?>">Mein Profil</a></li>
                <li><a href = "<?php echo url_for('@mein_archiv') ?>">Mein Archiv</a></li>
                <?php if (DokumentTable::countDokuments($sf_user->getGuardUser()->getId()) > 0): ?>
                    <li><a href = "<?php echo url_for('dokument') ?>">Meine Archivalien</a></li>
                <?php endif ?>
                <?php if (HaProjekteTable::countProjekte($sf_user->getGuardUser()->getId()) > 0): ?>
                    <li><a href = "<?php echo url_for('projekte') ?>">Meine Projekte</a></li>
                <?php endif ?>
                <li class = "divider"></li>
                <li>
                    <a href = "<?php echo url_for('sf_guard_signout') ?>">
                        <i class = "icon-off"></i> Ausloggen
                    </a>
                </li>
            </ul>
        <?php else:
            ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <small>Anmelden<b class="caret"></b></small>
            </a>
            <ul class="dropdown-menu">
                <li class="form">
                    <?php echo get_partial('sfGuardAuth/signin_form', array('form' => new sfGuardFormSignin())) ?>
                </li>
            </ul>
        <?php endif ?>
    </li>
</ul>
