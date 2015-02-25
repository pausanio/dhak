<?php if ($contactperson): ?>
    <footer class="contact">
        <hr
            <div class="media">
            <a class="pull-right" href="#">
                <?php if ($contactperson_filename) : ?>
                    <img class="media-object" src="<?php echo sfConfig::get('app_archivcontact_web') . $contactperson_filename ?>" alt="<?php echo $contactperson ?>" />
                <?php endif; ?>
            </a>
            <div class="media-body">
                <h2 class="media-heading">Falls Sie Fragen haben</h2>
                <?php $short = trim(strtolower(substr($contactperson, strrpos($contactperson, ' ')))) ?>
                <p>
                    schauen Sie doch mal in unsere <a href="<?php echo url_for('faq'); ?>">FAQs</a> oder schreiben Sie
                    mir über das <a href="<?php echo url_for('faq') . '?contact=' . $short; ?>">Kontaktformular</a>.
                    Ihr <?php echo $contactperson ?>.
                </p>
            </div>
        </div>
    </footer>
<?php endif; ?>