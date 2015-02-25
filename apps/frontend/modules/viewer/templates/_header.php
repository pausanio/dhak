<div id="header_container">
    <div class="row-fluid" id="header">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="span5">
                    <a class="brand" href="<?php echo url_for('homepage') ?>">
                        <img src="/images/dhak_ilogo.png" alt="Das digitale historische Archiv Köln" />
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
    </div>
</div>
<div class="pullup"><i class="icon-chevron-up"></i></div>
