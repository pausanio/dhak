<div class="cms-content">
    <div class="row-fluid">
        <h2>Meine Archivalien</h2>
        <?php echo cms_widget('dokument', 'hinweis', 'alert alert-info') ?>
        <?php
        $images = array(
            1 => 'abschrift',
            2 => 'digital',
            3 => 'foto',
            4 => 'kopie',
            5 => 'mikrofilm',
            6 => 'mikrofiche',
            7 => 'online',
            8 => 'druck',
            9 => 'archivexemplar'
        );
        ?>
        <?php if ($dokuments->count() > 0): ?>
            <?php foreach ($dokuments as $dokument): ?>
                <hr>
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="<?php echo $dokument->getThumb() ?>" alt="<?php echo $dokument->getTitel() ?>">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?php echo $dokument->getTitel() ?></strong>
                            <small>(<?php echo $dokument->getVorlagentyp()->getName() ?>)</small>
                        </h4>
                        <?php echo $dokument->getBeschreibung() ?>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif; ?>
    </div>
</div>