<div class="archiv-content" id="archiveWrapper">
    <div class="row-fluid <?php if ($contactperson): ?>with-contact<?php endif ?>">
        <div class="span4 sidebar">
            <?php include_partial('archiv/sidebar', array('tree' => $tree, 'path' => $path, 'current' => $current, 'parent' => null)) ?>
        </div>
        <div class="span8 content archiv">
            <div class="content-inner clearfix sticky">
                <?php include_partial('archiv/breadcrumb', array('ancestors' => $ancestors, 'dokument' => false, 'currentArchiv' => $currentArchiv)) ?>
                <h1>
                    <?php // (1) tektonik ?>
                    <?php if ($currentArchiv->getType() == 1): ?>
                        <?php echo $currentArchiv->getSignatur() ?>
                        <?php echo $currentArchiv->getName() ?>
                    <?php endif ?>

                    <?php // (2) bestand ?>
                    <?php if ($currentArchiv->getType() == 2): ?>
                        <?php echo $currentArchiv->getName() ?>
                        <?php if ($currentArchiv->getSignatur()): ?><span>(<?php echo $currentArchiv->getSignatur() ?>)</span><?php endif ?>
                        <?php $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($currentArchiv->getSignatur()) ?>
                        <?php if ($bestand->getLaufzeit()): ?>
                            <span class="laufzeit">
                                Laufzeit: <?php echo $bestand->getLaufzeit() ?>
                            </span>
                        <?php endif ?>
                    <?php endif ?>

                    <?php // (3) klassifikation ?>
                    <?php if ($currentArchiv->getType() == 3): ?>
                        <?php $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($currentArchiv->getSignatur()) ?>
                        <?php if (is_object($bestand)): ?>
                            <a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug(DhastkImporter::INTBESTAND) . '&id=' . $bestand->getArchivId() . '&slug=' . $bestand->getSignaturSlug()) ?>">
                                <?php echo $bestand->getBestandsname() ?>
                                (<?php echo $bestand->getBestandSig() ?>)
                            </a>
                        <?php endif ?>
                        <br><?php echo $currentArchiv->getName() ?>
                        <?php if ($bestand->getLaufzeit()): ?>
                            <span class="laufzeit">
                                Laufzeit: <?php echo $bestand->getLaufzeit() ?>
                            </span>
                        <?php endif ?>
                    <?php endif ?>

                    <?php // (4) bandserie ?>
                    <?php if ($currentArchiv->getType() == 4): ?>
                        <?php $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($currentArchiv->getSignatur()) ?>
                        <?php if (is_object($bestand)): ?>
                            <a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug(DhastkImporter::INTBESTAND) . '&id=' . $bestand->getArchivId(). '&slug=' . $bestand->getSignaturSlug()) ?>">
                                <?php echo $bestand->getBestandsname() ?>
                                (<?php echo $bestand->getBestandSig() ?>)
                            </a>
                        <?php endif ?>
                        <br><?php echo $currentArchiv->getName() ?>
                        <?php if ($bestand->getLaufzeit()): ?>
                            <span class="laufzeit">
                                Laufzeit: <?php echo $bestand->getLaufzeit() ?>
                            </span>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if ($currentArchiv->getType() > 0): ?>
                        <span class="meta">
                            <?php echo $currentArchiv->getModel() ?>
                            (<?php echo $currentArchiv->getCountVe() ?> Verzeichnungseinheiten,
                            <?php echo $currentArchiv->getCountDocs() + $currentArchiv->getCountUserdocs() ?> Einträge)
                            <?php /* <?php if ($currentArchiv->getCountUserdocs() > 0): ?>, <?php echo $currentArchiv->getCountUserdocs() ?> Nutzergenierte Einträge<?php endif ?>) */ ?>
                        </span>
                    <?php endif ?>
                </h1>
                <?php include_partial('archiv/description', array('currentArchiv' => $currentArchiv)) ?>
                <?php include_partial('archiv/verzeichnungseinheiten', array('verzeichnungseinheiten' => $verzeichnungseinheiten, 'bestand_signatur' => $currentArchiv->getSignatur(), 'myVEs' => (isset($myVEs)?$myVEs:array()))) ?>
                <?php include_component('archiv', 'userDokumente', array('currentArchiv' => $currentArchiv)) ?>
                <?php include_partial('archiv/verweise', array('verweise' => $verweise)) ?>
                <?php include_partial('archiv/contact', array('contactperson' => $contactperson, 'contactperson_filename' => $contactperson_filename)) ?>
            </div>
        </div>
    </div>
</div>
