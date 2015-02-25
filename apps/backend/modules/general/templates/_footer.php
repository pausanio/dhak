<?php if ($sf_user->isAuthenticated()): ?>
    <footer>
        <div class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <div class="nav-collapse collapse">
                        <span class="navbar-text pull-left">
                            <i class="icon-info-sign icon-white"></i>
                            <b>Support:</b>
                            E-Mail <a href="mailto:mettenheimer@pausanio.de">mettenheimer@pausanio.de</a> &middot;
                            Telefon +49 221 935497-30
                        </span>

                        <?php if ($sf_user->isAuthenticated()): ?>
                            <?php include_partial('general/account') ?>
                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php

 endif ?>