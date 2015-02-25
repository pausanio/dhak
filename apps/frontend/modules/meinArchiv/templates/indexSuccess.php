<?php use_helper('Text'); ?>
<div class="mein-archiv">
    <div class="row-fluid">
        <h2>Mein Archiv</h2>
    </div>
    <div class="text">
        <?php if($sf_user->isAuthenticated()): ?>
            <?php echo cms_widget('mein-archiv', 'Ihre gemerkten Archivobjekte') ?>
        <?php else: ?>
            <?php echo cms_widget('mein-archiv', 'Sie müssen angemeldet sein, um diese Funktion nutzen zu können.', 'alert') ?>
        <?php endif; ?>
    </div>
    <hr/>
    <h3>Meine gespeicherten Tektonikpunkte, Bestände und Klassifikationen</h3>
    <?php if ($archiv && $archiv->count() > 0): ?>
        <ul class="ve_list unstyled a">
        <?php foreach ($archiv as $bookmark): ?>
            <li id="ve_<?php echo $bookmark->getId() ?>">
                <?php
                $bookmarkTitle = $bookmark->getArchiv()->getMeinArchivTitle();
                ?>
                <button data-meinarchiv-a-bookmarkid="<?php echo $bookmark->getId() ?>" type="button" class="close tiptool" title="<?php echo $bookmarkTitle ?> aus 'Mein Archiv' löschen">&times;</button>
                <a href="<?php echo url_for('@lesesaal?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $bookmark->getArchiv()->getId() . '&type='.$bookmark->getArchiv()->getSluggedType().'&slug=' . $bookmark->getArchiv()->getSignaturSlug()) ?>">
                    <h3>
                        <small>
                            <b><?php echo $bookmark->getArchiv()->getSignatur(); ?></b>
                            | <?php echo $bookmark->getArchiv()->getTypeString() ?>
                            | (Gespeichert am <?php echo $bookmark->getDateTimeObject('created_at')->format('d.m.Y'); ?>)
                            <?php if(!empty($bookmark->personal_comments)): ?>
                            | Private Notizen: <i><?php echo truncate_text($bookmark->personal_comments, 50, ' ...', true); ?></i>
                            <?php endif; ?>
                        </small><br>

                        <?php
                        $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($bookmark->getArchiv()->getSignatur());
                        if($bookmark->getArchiv()->getType() == DhastkImporter::INTKLASSIFIKATION){
                            $tektonikTitle = ($bestand?$bestand->getBestandsname() :'') .': '. $bookmark->getArchiv()->getName();
                        }
                        else{
                            $tektonikTitle = $bookmark->getArchiv()->getName();
                        }
                        echo $tektonikTitle;
                        ?>
                    </h3>
                </a>
            </li>
        <?php endforeach ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">
            <strong>Keine Tektonikpunkte, Bestände oder Klassifikationen gemerkt.</strong>
        </div>
    <?php endif; ?>
    <h3>Meine gespeicherten Signaturen</h3>
    <?php if ($verzeichnungseinheiten && $verzeichnungseinheiten->count() > 0): ?>
    <ul class="ve_list unstyled ve">
        <?php foreach ($verzeichnungseinheiten as $bookmark): ?>
            <li id="ve_<?php echo $bookmark->getId() ?>">
                <button data-meinarchiv-ve-bookmarkid="<?php echo $bookmark->getId() ?>" type="button" class="close tiptool" title="Signatur aus 'Mein Archiv' löschen">&times;</button>
                <a href="<?php echo url_for('@lesesaal_verzeichnungseinheit?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $bookmark->getVerzeichnungseinheit()->getId() . '&slug=' . $bookmark->getVerzeichnungseinheit()->getSignaturSlug()) ?>">
                    <h3>
                        <small>
                            <b>(<?php echo $bookmark->getVerzeichnungseinheit()->getBestandSig() ?>) <?php echo $bookmark->getVerzeichnungseinheit()->getSignatur(); ?></b>
                            <?php if ($bookmark->getVerzeichnungseinheit()->getLaufzeit()): ?>
                                | Laufzeit: <?php echo $bookmark->getVerzeichnungseinheit()->getLaufzeit() ?>
                            <?php endif ?>
                            | <?php echo $bookmark->getVerzeichnungseinheit()->getCountDocs() ?> Einträge
                            | (Gespeichert am <?php echo $bookmark->getDateTimeObject('created_at')->format('d.m.Y'); ?>)
                            <?php if(!empty($bookmark->personal_comments)): ?>
                                | Private Notizen: <i><?php echo truncate_text($bookmark->personal_comments, 50, ' ...', true); ?></i>
                            <?php endif; ?>
                        </small><br>
                        <?php if ($bookmark->getVerzeichnungseinheit()->getArchivgutart() == 'Urkunde_vormodern'): ?>
                            <?php echo truncate_text($bookmark->getVerzeichnungseinheit()->getBeschreibung(), 100, ' ...', true); ?>
                        <?php else: ?>
                            <?php echo $bookmark->getVerzeichnungseinheit()->getTitel(); ?>
                            <p><?php echo truncate_text($bookmark->getVerzeichnungseinheit()->getBeschreibung(), 250, ' ...', true); ?></p>
                        <?php endif ?>
                    </h3>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
    <?php else: ?>
        <div class="alert alert-info">
            <strong>Keine Signaturen gemerkt.</strong> Nutzen Sie die Speicher-Möglichkeit im Viewer.
        </div>
    <?php endif; ?>
    <h3>Meine gespeicherten Bildansichten</h3>
    <?php if ($dokumente && $dokumente->count() > 0): ?>
    <ul class="ve_list unstyled dok">
        <?php foreach ($dokumente as $bookmark): ?>
            <li id="dok_<?php echo $bookmark->getId() ?>">
                <button data-meinarchiv-dok-bookmarkid="<?php echo $bookmark->getId() ?>" type="button" class="close tiptool" title="Bildansicht aus 'Mein Archiv' löschen">&times;</button>
                <a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $bookmark->getDokumente()->getId() . '&slug=' . $bookmark->getDokumente()->getSignaturSlug()) ?>" target="_blank">
                    <h3>
                        <small>
                            <b>(<?php echo $bookmark->getDokumente()->getBestandSig() ?>) <?php echo $bookmark->getDokumente()->getSignatur(); ?></b>
                           | Bilddatei <?php echo $bookmark->getDokumente()->getPosition() ?>
                           | (Gespeichert am <?php echo $bookmark->getDateTimeObject('created_at')->format('d.m.Y'); ?>)
                           <?php if(!empty($bookmark->personal_comments)): ?>
                                | Private Notizen: <i><?php echo truncate_text($bookmark->personal_comments, 50, ' ...', true); ?></i>
                           <?php endif; ?>
                        </small><br>
                    </h3>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
    <?php else: ?>
    <div class="alert alert-info">
        <strong>Keine Bildansichten gemerkt.</strong> Nutzen Sie die Speicher-Möglichkeit im Viewer.
    </div>
    <?php endif; ?>
</div>
<?php include_partial('default/confirmDialog', array('idSuffix' => '','confirmTitle' => cms_widget('mein-archiv', 'Gemerktes Archivobjekt löschen?'),'confirmText' => cms_widget('mein-archiv', 'Wollen Sie dieses Archivobjekt aus "Mein Archiv" löschen?'))) ?>
<script type="application/javascript">
    $(document).ready(function () {
        var id, title, type, liId;
        $('.tiptool').tooltip()
        $('button.close').click(function (e) {
            var dokid = $(this).data('meinarchiv-dok-bookmarkid'),
                veid = $(this).data('meinarchiv-ve-bookmarkid'),
                aid = $(this).data('meinarchiv-a-bookmarkid');

            liId = $(this).parent().attr('id');

            if (veid != undefined) {
                id = veid;
                title = 'Signatur';
                type = 've';
            }
            else if(dokid != undefined) {
                id = dokid;
                title = 'Bildansicht';
                type = 'dok';
            }
            else{
                id = aid;
                title = 'Tektonikpunkt';
                type = 'a';
            }
            $('#confirmModal').modal();
        });
        $('#confirmModal button.btn-primary').click(function (e) {
            if (id == undefined && type == undefined && title == undefined && liId == undefined) {
                return;
            }
            $.ajax({
                type: "DELETE",
                dataType: 'json',
                url: '<?php echo url_for('@mein_archiv_delete') ?>',
                data: JSON.stringify({ id: id, type: type })
            })
            .done(function () {
                //remove li
                $('#'+liId).remove();
                $('.mein-archiv').flash(title + ' wurde gelöscht', {type: 'success'});
                if ($('ul.' + type + ' li').length == 0) {
                   $('ul.' + type).html('<div class="alert alert-info"><strong>Kein ' + title + ' gemerkt.</strong> Nutzen Sie die Speicher-Möglichkeit im Viewer.</div>');
                }
            })
            .fail(function () {
                $('.mein-archiv').flash('Es ist ein Fehler beim Löschen aufgetreten', {type: 'error'});
            })
            .always(function(){
                $('#confirmModal').modal('hide');
                id, title, type, li = undefined;
            });
        });
    });
</script>