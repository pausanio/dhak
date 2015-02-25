<?php $lang = $sf_user->getCulture() ?>

<?php if (isset($subnavi)) echo htmlspecialchars_decode($subnavi) ?>

<div class="cms-content">
    <div class="row-fluid">

        <div class="span4">
            <?php include_partial('patenschaft_leftcolumn', array('type' => $type)) ?>
        </div>

        <div class="span8">

            <div class="form-bar clearfix">
                <form class="filter" action="" method="get" class="form-inline">
                    <?php $limit = isset($_GET['limit']) ? $_GET['limit'] : 9; ?>
                    <select class="pull-right" name="limit" onChange="javascript:this.form.submit();">
                        <?php for ($i = 1; $i < 10; $i++): ?>
                            <option value="<?php echo $i * 9 ?>" <?php if ($limit == $i * 9): ?>selected="selected"<?php endif; ?>>
                                <?php echo $i * 9 ?> pro Seite
                            </option>
                        <?php endfor; ?>
                    </select>
                </form>
            </div>

            <div class="gallery">
                <?php foreach ($objects as $object): ?>
                    <div class="media">
                        <a class="pull-left media-object-container" href="<?php echo url_for('patenschaft_show', array('id' => $object->getId())) ?>">
                            <img class="media-object" src="/images/patenobjekt/thumb/<?php echo $object->getPatenobjektPhotos()->get(0)->getFilename(); ?>">
                            <?php if ($object->getVerfuegbar() != 1): ?>
                                <img src="/images/vergeben.png" class="assigned" />
                            <?php endif; ?>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a class="pull-left media-object-container" href="<?php echo url_for('patenschaft_show', array('id' => $object->getId())) ?>">
                                    <?php echo $object->getTitel(); ?>
                                </a>
                            </h4>
                            <?php if ($object->getVerfuegbar() != 1): ?>
                                <p class="clearfix">Für dieses Objekt konnte ein Restaurierungspate gefunden werden!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>

                <?php if ($pager->haveToPaginate()): ?>
                    <div class="pagination pagination-small">
                        <ul>
                            <li>
                                <a href="<?php echo url_for('patenschaft') ?>/<?php echo $type; ?>?page=<?php echo $pager->getPreviousPage() ?>" class="prev"><?php echo __('Seite zurück') ?></a>
                            </li>
                            <?php foreach ($pager->getLinks() as $page): ?>
                                <li <?php echo ($page == $pager->getPage()) ? ' class="active"' : ''; ?>>
                                    <a href="<?php echo url_for('patenschaft') ?>/<?php echo $type; ?>?page=<?php echo $page ?>">
                                        <?php echo $page ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <a href="<?php echo url_for('patenschaft/index') ?>/<?php echo $type; ?>?page=<?php echo $pager->getNextPage() ?>" class="next"><?php echo __('Nächste Seite') ?></a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
