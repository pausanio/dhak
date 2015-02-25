<div class="row" id="header">
    <div class="navbar span12">
        <div class="navbar-inner">
            <div class="row-fluid">
                <div class="span5">
                    <a class="brand" href="<?php echo url_for('homepage') ?>">
                        <img src="/images/dhak_ilogo.png" alt="" />
                    </a>
                    <a class="beta" href="/de/info/beta">beta</a>
                    <p>Gefördert durch die <a href="http://www.dfg.de"><img src="/images/logo_dfg.jpg" alt="DFG" /></a></p>
                </div>
                <div class="span7">
                    <?php include_partial('default/usermenu') ?>
                    <ul id="menu_main" class="nav nav-pills pull-right">
                        <?php include_partial('default/mainmenu') ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php if ($sf_user->hasFlash('error')): ?>
            <div class="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Fehler!</strong> <?php echo $sf_user->getFlash('error') ?>
            </div>
        <?php endif ?>
    </div>
</div>