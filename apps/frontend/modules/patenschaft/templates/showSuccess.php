<?php $pic = $sf_request->getParameter('pic', 0) ?>
<?php $lang = $sf_user->getCulture() ?>

<?php if (isset($subnavi)) echo htmlspecialchars_decode($subnavi) ?>

<div class="cms-content">
    <div class="row-fluid">

        <div class="span4">
            <?php include_partial('patenschaft_leftcolumn', array('type' => $object->getType())) ?>
        </div>

        <div class="span8 itemDesc">
            <div class="gallery">
                <div class="<!--largeView-->">
                    <div class="<!--innerShadow-->"></div>
                    <img src="/images/patenobjekt/large/<?php echo $object->getPatenobjektPhotos()->get($pic)->getFilename(); ?>" alt="Großansicht" />
                </div>

                <div class="pagination pagination-small">
                    <ul>
                        <?php for ($i = 0; $i < $object->getPatenobjektPhotos()->count(); $i++): ?>
                            <li <?php if ($i == $pic): ?>class="active"<?php endif; ?>>
                                <a href="?pic=<?php echo $i ?>"> <?php echo $i + 1 ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span6">
                    <h3><?php echo $object->getTitel(); ?></h3>
                    <h4>Das Objekt</h4>
                    <p><?php echo htmlspecialchars_decode($object->getBeschreibung()); ?></p>
                    <h4>Inhalt</h4>
                    <p><?php echo htmlspecialchars_decode($object->getInhalt()); ?></p>
                    <h4>Zustand</h4>
                    <p><?php echo htmlspecialchars_decode($object->getzustand()); ?></p>
                    <h4>Restaurierungskonzept</h4>
                    <p><?php echo htmlspecialchars_decode($object->getRestaurierung()); ?></p>
                </div>
                <div class="span6">
                    <p class="itemNo">&nbsp;</p>
                    <h4>Maßnahmen</h4>
                    <p>
                        <?php echo htmlspecialchars_decode($object->getMassnahmen()); ?>
                    </p>
                    <p>
                        <strong>Ich interessiere mich für eine Restaurierungspatenschaft für dieses Objekt.</strong>
                    </p>
                    <p>
                        <a class="formee-button formee-button-alt formee-button-small" href="<?php echo url_for('patenschaft_kontakt') ?>">
                            Kontakt
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>