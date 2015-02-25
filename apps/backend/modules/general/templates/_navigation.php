<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo url_for('@homepage') ?>">DHAK</a>
            <div class="nav-collapse">
                <ul class="nav">

                    <?php if ($sf_user->hasCredential('backend')) : ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">CMS <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo link_to('Aktuelles', 'ha_news') ?></li>
                                <li><?php echo link_to('Text-Widgets', 'cms_text') ?></li>
                                <li><?php echo link_to('FAQ', 'ha_faq') ?></li>
                                <li><?php echo link_to('Slider', 'cms_slider') ?></li>
                                <li><?php echo link_to('Info Seiten', 'cms_info') ?></li>
                            </ul>
                        </li>
                    <?php endif ?>

                    <?php if ($sf_user->hasCredential('backend')) : ?>
                        <li><?php echo link_to('Projekte', 'ha_projekte') ?></li>
                    <?php endif ?>

                    <?php if ($sf_user->hasCredential('patenschaften')) : ?>
                        <li><?php echo link_to('Patenschaften', 'patenobjekt') ?></li>
                    <?php endif ?>

                    <?php if ($sf_user->hasCredential('backend')) : ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Archiv <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo url_for('archiv/tree') ?>">Lesesaal</a></li>
                                <li class="divider"></li>
                                <li><?php echo link_to('Archiv', 'archiv') ?></li>
                                <li><?php echo link_to('Bestände', 'bestand') ?></li>
                                <li><?php echo link_to('Verzeichnungseinheiten', 'verzeichnungseinheit') ?></li>
                                <li><?php echo link_to('Dokumente', 'dokument') ?></li>
                                <li class="divider"></li>
                                <li><?php echo link_to('Benutzerdaten zuordnen', 'dokument_findarchiv') ?></li>
                                <li><?php echo link_to('Import', 'import') ?></li>
                            </ul>
                        </li>
                    <?php endif ?>

                    <?php if ($sf_user->hasCredential('backend')) : ?>
                        <li><?php echo link_to('Benutzer', 'sf_guard_user') ?></li>
                    <?php endif ?>

                    <?php if ($sf_user->hasCredential('backend')) : ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dokumentation <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo link_to('Anwender', 'doc_user') ?></li>
                                <li><?php echo link_to('Entwickler', 'doc_dev') ?></li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>

            </div>
        </div>
    </div>
</div>