<?php if (count($verweise) > 0): ?>
    <hr>
    <h2>
        <?php echo count($verweise) ?>
        <?php if (count($verweise) > 1): ?>Verweise<?php else: ?>Verweis<?php endif; ?>
    </h2>
    <ul class="ve_list unstyled">
        <?php foreach ($verweise as $verweis): ?>
            <li>
                <h3>
                    <?php echo $verweis->getTitel(); ?>
                    <?php if ($verweis->getLaufzeit()): ?>
                        <br/>
                        <small>
                            Laufzeit: <?php echo $verweis->getLaufzeit(); ?>
                        </small>
                    <?php endif ?>
                </h3>
                <?php $desc = $verweis->getBeschreibung(); ?>
                <?php if (!empty($desc)): ?>
                    <p><?php echo $desc; ?></p>
                <?php endif; ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif; ?>

