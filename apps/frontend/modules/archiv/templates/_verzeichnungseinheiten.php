<?php use_helper('Text'); ?>
<?php if (count($verzeichnungseinheiten) > 0): ?>
    <hr>
    <h2>
        Verzeichnungseinheiten
        (<?php echo count($verzeichnungseinheiten) ?>)
    </h2>

    <div class="filter clearfix">
        <form class="form-inline">
            <div class="ve_filterbox">
                <div class="field">
                    <label class="">Filtern:</label>
                    <input id="ve_filter" type="text" class="text input-xxlarge" name="ve_search" placeholder="Verzeichnungseinheiten durchsuchen...">
                </div>
            </div>
        </form>
    </div>

    <ul class="ve_list unstyled">
        <?php foreach ($verzeichnungseinheiten as $verzeichnungseinheit): ?>
            <li>
                <a href="<?php echo url_for('@lesesaal_verzeichnungseinheit?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $verzeichnungseinheit->getId() . '&slug=' . $verzeichnungseinheit->getSignaturSlug()) ?>">
                    <h3>
                        <small>
                            <b>(<?php echo $bestand_signatur ?>) <?php echo $verzeichnungseinheit->getSignatur(); ?></b>
                            <?php if ($verzeichnungseinheit->getLaufzeit()): ?>
                                | Laufzeit: <?php echo $verzeichnungseinheit->getLaufzeit() ?>
                            <?php endif ?>
                            | <?php echo $verzeichnungseinheit->getCountDocs() ?> Einträge
                            <?php if(in_array($verzeichnungseinheit->id, $myVEs)): ?>
                            | <span class="label label-info" title="In 'Mein Archiv' gespeichert">Mein Archiv</span>
                            <?php endif; ?>
                        </small><br/>
                        <?php if ($verzeichnungseinheit->getArchivgutart() == 'Urkunde_vormodern'): ?>
                            <?php echo truncate_text($verzeichnungseinheit->getBeschreibung(), 100, ' ...', true); ?>
                        <?php else: ?>
                            <?php echo $verzeichnungseinheit->getTitel(); ?>
                            <p><?php echo truncate_text($verzeichnungseinheit->getBeschreibung(), 250, ' ...', true); ?></p>
                        <?php endif ?>
                    </h3>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>