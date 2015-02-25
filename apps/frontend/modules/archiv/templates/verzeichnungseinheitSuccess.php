<div class="archiv-content" id="archiveWrapper">
    <div class="row-fluid">
        <div class="span4 sidebar">
            <?php include_partial('archiv/sidebar', array('tree' => $tree, 'path' => $path, 'current' => $current, 'parent' => null)) ?>
        </div>
        <div class="span8 content archiv">
            <div class="content-inner clearfix sticky">
                <?php include_partial('archiv/breadcrumb', array('ancestors' => $ancestors, 'dokument' => false, 've' => $verzeichnungseinheit, 'currentArchiv' => $currentArchiv)) ?>

                <h1>
                    <?php $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($currentArchiv->getSignatur()) ?>
                    <?php if (is_object($bestand)): ?>
                        <a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug(DhastkImporter::INTBESTAND) . '&id=' . $bestand->getArchivId() . '&slug=' . $bestand->getSignaturSlug()) ?>">
                            <?php echo $bestand->getBestandsname() ?>
                            (<?php echo $bestand->getBestandSig() ?>)
                            <?php echo $verzeichnungseinheit->getSignatur() ?>
                        </a>
                    <?php endif ?>
                    <?php if ($verzeichnungseinheit->getArchivgutart() != 'Urkunde_vormodern'): ?>
                        <br>
                        <?php echo $verzeichnungseinheit->getTitel() ?>
                    <?php endif ?>
                    <?php $laufzeit = $verzeichnungseinheit->getLaufzeit() ?>
                    <?php if (!empty($laufzeit)): ?>
                        <span class="laufzeit">
                            Laufzeit: <?php echo $laufzeit ?>
                        </span>
                    <?php endif ?>
                    <span class="meta">
                        Verzeichnungseinheit (<?php echo $verzeichnungseinheit->getCountDocs() ?> Einträge)
                    </span>
                </h1>

                <div class="expander">
                    <?php $desc = $verzeichnungseinheit->getBeschreibung(); ?>
                    <?php echo (!empty($desc) ? $desc : ''); ?>
                </div>

                <dl class="dl-horizontal">
                    <?php $enthaelt = $verzeichnungseinheit->getEnthaelt(); ?>
                    <?php if (!empty($enthaelt)): ?>
                        <dt>Enthält:</dt>
                        <dd><?php echo $enthaelt ?></dd>
                    <?php endif; ?>
                </dl>

                <?php include_component('archiv', 'dokumente', array('veId' => $verzeichnungseinheit->id)) ?>
                <?php include_component('archiv', 'vorgaenge', array('bestandSig' => $verzeichnungseinheit->getBestandSig(), 'veSignatur' => $verzeichnungseinheit->getSignatur())) ?>

            </div>
        </div>
    </div>
</div>
