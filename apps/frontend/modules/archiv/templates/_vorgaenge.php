<?php if (count($vorgaenge) > 0): ?>
    <hr>
    <h2>
        <?php echo count($vorgaenge) ?>
        <?php if (count($vorgaenge) > 1): ?>Vorgänge<?php else: ?>Vorgang<?php endif; ?>
    </h2>
    <ul class="ve_list unstyled">
        <?php foreach ($vorgaenge as $vorgang): ?>
            <li>
                <h3>
                    <?php echo $vorgang->getTitel(); ?>
                    <?php if ($vorgang->getLaufzeit()): ?>
                        <br/>
                        <small>
                            Laufzeit: <?php echo $vorgang->getLaufzeit(); ?>
                            Umfang: <?php echo $vorgang->getUmfang(); ?>
                        </small>
                    <?php endif ?>
                </h3>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

