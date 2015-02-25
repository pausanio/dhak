<?php use_helper('Text'); ?>
<?php
if ($verzeichnungseinheit):
    $titel = $verzeichnungseinheit->getTitel();
else:
    $titel = $dokument->getTitel();
endif;
?>

<div class="accordion" id="accordion_signature">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_signature" href="#findmitteleintrag">
                <i class="icon-chevron-right"></i>
                Findmitteleintrag
                <span class="findmittel"><small><?php echo truncate_text($titel, 35, ' ...', true); ?></small></span>
            </a>
        </div>
        <div id="findmitteleintrag" class="accordion-body collapse">
            <div class="accordion-inner">
                <h1><?php echo $titel ?></h1>
                <?php if ($dokument->getBeschreibung()): ?>
                    <p></p>
                    <div class="expander">
                        <p><?php echo nl2br($dokument->getBeschreibung()) ?></p>
                    </div>
                <?php endif ?>

                <?php if ($dokument->getUsergenerated()): ?>
                    <?php echo cms_widget('lesesaal', 'hinweis_userupload', 'alert alert-info') ?>
                <?php endif ?>

                <div class="row-fluid dokument">
                    <dl class="dl-list">
                        <?php if ($dokument->getDatierung()): ?>
                            <dt>Datierung:</dt>
                            <dd><?php echo $dokument->getDatierung() ?></dd>
                        <?php endif ?>
                        <?php if ($dokument->getFolio()): ?>
                            <dt>Folio:</dt>
                            <dd><?php echo $dokument->getFolio() ?></dd>
                        <?php endif ?>
                        <?php if ($dokument->getVorlagentyp()): ?>
                            <dt>Vorlagentyp:</dt>
                            <dd><?php echo $dokument->getVorlagentyp() ?></dd>
                        <?php endif ?>
                        <?php if ($dokument->getEinsteller()): ?>
                            <dt>Einsteller:</dt>
                            <dd><?php echo $dokument->getEinsteller() ?></dd>
                        <?php endif ?>
                        <?php $creator = $dokument->getCreator(); ?>
                        <?php if ($creator): ?>
                            <dd><?php echo $creator->getProfile()->getTitleFront() ?><?php echo $creator->getFirstName() ?> <?php echo $creator->getLastName() ?> <?php echo $creator->getProfile()->getTitleRear() ?>
                                <br/>
                                <small><?php echo $creator->getProfile()->getInstitution() ?></small>
                            </dd>
                            <dt>Eingestellt am:</dt>
                            <dd><?php echo date("d.m.Y \- H:i", strtotime($dokument->getCreatedAt())); ?></dd>
                        <?php endif ?>
                        <?php if ($dokument->getKommentar()): ?>
                            <dt>Kommentar:</dt>
                            <dd><?php echo $dokument->getKommentar() ?></dd>
                        <?php endif ?>
                        <?php if ($dokument->getCopyright()): ?>
                            <dt>Copyright:</dt>
                            <dd><?php echo $dokument->getCopyright() ?></dd>
                        <?php endif ?>
                    </dl>
                </div>

                <?php if ($verzeichnungseinheit): ?>
                    <div class="row-fluid ve">
                        <h3>Verzeichnungseinheit</h3>
                        <dl class="dl-list">
                            <?php if ($verzeichnungseinheit->getTitel()): ?>
                                <dt>Titel:</dt>
                                <dd><?php echo $verzeichnungseinheit->getTitel() ?></dd>
                            <?php endif ?>
                            <?php if ($verzeichnungseinheit->getLaufzeit()): ?>
                                <dt>Laufzeit:</dt>
                                <dd><?php echo $verzeichnungseinheit->getLaufzeit() ?></dd>
                            <?php endif ?>
                            <?php if ($verzeichnungseinheit->getBem()): ?>
                                <dt>Bemerkung:</dt>
                                <dd><?php echo $verzeichnungseinheit->getBem() ?></dd>
                            <?php endif ?>
                            <?php if ($verzeichnungseinheit->getSperrvermerk()): ?>
                                <dt>Sperrvermerk:</dt>
                                <dd><?php echo $verzeichnungseinheit->getSperrvermerk() ?></dd>
                            <?php endif ?>
                            <?php $enthaelt = $verzeichnungseinheit->getEnthaelt(); ?>
                            <?php if (!empty($enthaelt)): ?>
                                <dt>Enthält:</dt>
                                <dd><?php echo $enthaelt ?></dd>
                            <?php endif ?>
                            <?php $desc = $verzeichnungseinheit->getBeschreibung(); ?>
                            <?php if (!empty($desc)): ?>
                                <dt>Beschreibung:</dt>
                                <dd><?php echo $desc ?></dd>
                            <?php endif ?>
                        </dl>
                        <?php if ($verzeichnungseinheit): ?>
                            <?php include_component('archiv', 'vorgaenge', array('bestandSig' => $verzeichnungseinheit->getBestandSig(), 'veSignatur' => $verzeichnungseinheit->getSignatur())) ?>
                            <?php include_component('archiv', 'dokumente', array('veId' => $verzeichnungseinheit->id)) ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

            </div>
        </div>
    </div>
    <?php if($verzeichnungseinheit): ?>
    <?php $notizen = empty($myVE->personal_comments)?'':$myVE->personal_comments;?>
    <div class="accordion-group" id="signature_comments">
        <div class="accordion-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_signature" href="#signature_privatenotizen">
                <i class="icon-chevron-right"></i>
                Private Notizen
                <span class="notizen"><small><?php echo truncate_text($notizen, 35, ' ...', true); ?></small></span>
            </a>
        </div>
        <div id="signature_privatenotizen" class="accordion-body collapse">
            <div class="accordion-inner">
            <?php if(!$sf_user->isAuthenticated()): ?>
                <?php echo cms_widget('viewer-seitenleiste', 'Sie müssen angemeldet sein, um diese Funktion nutzen zu können.', 'alert') ?>
            <?php endif; ?>
                <textarea <?php if(!$sf_user->isAuthenticated()): ?>disabled <?php endif; ?>class="comments" rows="10" id="signature_privatenotizen_textarea" name="privatenotizen_textarea" placeholder="Speichern Sie hier private Notizen zu dieser Signatur."><?php echo $notizen;?></textarea>
                <div>
                    <button <?php if(!$sf_user->isAuthenticated()): ?>disabled <?php endif; ?> id="signature_comments_submit" class="submit btn btn-info">Speichern</button>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-info">
            Notizen können Sie hier nur im "Bildansicht" Tab speichern.
        </div>
    <?php endif ?>
</div>
<hr/>