<div class="description">

    <?php // Tektonik ?>
    <?php if ($currentArchiv->getType() == DhastkImporter::INTARCHIV || $currentArchiv->getType() == DhastkImporter::INTROOT): ?>
        <?php if ($currentArchiv->getUserDescription()): ?>
            <div class="expander">
                <?php echo $currentArchiv->getUserDescription() ?>
            </div>
        <?php endif ?>
        <?php if ($currentArchiv->getBeschreibung()): ?>
            <div class="expander">
                <p><?php echo nl2br($currentArchiv->getBeschreibung()) ?></p>
            </div>
        <?php endif ?>
    <?php endif ?>

    <?php // Bestand ?>
    <?php if ($currentArchiv->getType() == DhastkImporter::INTBESTAND): ?>
        <?php $bestand = Doctrine_Core::getTable('Bestand')->findOneByBestandSig($currentArchiv->getSignatur()) ?>
        <?php if (is_object($bestand)): ?>
            <?php if ($currentArchiv->getUserDescription()): ?>
                <div class="expander">
                    <?php echo $currentArchiv->getUserDescription() ?>
                </div>
            <?php endif ?>
            <?php if ($bestand->getBestandInhalt()): ?>
                <div class="expander">
                    <p><?php echo nl2br($bestand->getBestandInhalt()) ?></p>
                </div>
            <?php endif ?>
            <dl class="dl-horizontal">
                <?php if ($bestand->getUmfang()): ?>
                    <dt>Umfang:</dt>
                    <dd><?php echo nl2br($bestand->getUmfang()) ?></dd>
                <?php endif ?>
                <?php if ($bestand->getBestandsgeschichte()): ?>
                    <dt>Bestandsgeschichte:</dt>
                    <dd><?php echo nl2br($bestand->getBestandsgeschichte()) ?></dd>
                <?php endif ?>
                <?php if ($bestand->getSperrvermerk()): ?>
                    <dt>Sperrvermerk:</dt>
                    <dd><?php echo nl2br($bestand->getSperrvermerk()) ?></dd>
                <?php endif ?>
                <?php if ($bestand->getAbgStelle()): ?>
                    <dt>Abg-Stelle:</dt>
                    <dd><?php echo nl2br($bestand->getAbgStelle()) ?></dd>
                <?php endif ?>
                <?php if ($bestand->getRechtsstatus()): ?>
                    <dt>Rechtsstatus:</dt>
                    <dd><?php echo nl2br($bestand->getRechtsstatus()) ?></dd>
                <?php endif ?>
            </dl>
        <?php endif ?>
    <?php endif ?>

    <?php // Klassifikation ?>
    <?php if ($currentArchiv->getType() == DhastkImporter::INTKLASSIFIKATION): ?>
        <?php if ($currentArchiv->getUserDescription()): ?>
            <div class="expander">
                <?php echo $currentArchiv->getUserDescription() ?>
            </div>
        <?php endif ?>
        <?php if ($currentArchiv->getBeschreibung()): ?>
            <div class="expander">
                <p><?php echo nl2br($currentArchiv->getBeschreibung()) ?></p>
            </div>
        <?php endif ?>
    <?php endif ?>

    <?php // Bandserie ?>
    <?php if ($currentArchiv->getType() == DhastkImporter::INTBANDSERIE): ?>
        <?php if ($currentArchiv->getUserDescription()): ?>
            <div class="expander">
                <?php echo $currentArchiv->getUserDescription() ?>
            </div>
        <?php endif ?>
        <?php if ($currentArchiv->getBeschreibung()): ?>
            <div class="expander">
                <p><?php echo nl2br($currentArchiv->getBeschreibung()) ?></p>
            </div>
        <?php endif ?>
    <?php endif ?>

</div>
