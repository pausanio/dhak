<?php if (!isset($ve)) $ve = false; ?>
<div id="breadCrumb" class="breadcrumb module">
    <div style="overflow:hidden; position:relative; width: 90%;float: left;">
        <div>
            <ul>
                <li>
                    <a href="<?php echo url_for('@lesesaal_root') ?>">
                        Home
                    </a>
                </li>
                <?php $i = 0 ?>
                <?php if ($ancestors): ?>
                    <?php foreach ($ancestors as $ancestor): ?>
                        <?php if ($i > 0): ?>
                            <li>
                                <a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($ancestor['type']) . '&id=' . $ancestor['id'] . '&slug=' . $ancestor['signatur'] . '+' . $ancestor['name']) ?>">
                                    <?php if ($ancestor['type'] == DhastkImporter::INTBESTAND || $ancestor['type'] == DhastkImporter::INTVERZEICHNISEINHEIT): ?>
                                    <strong><?php endif; ?>
                                        <?php echo $ancestor['name'] ?>
                                    <?php if ($ancestor['type'] == DhastkImporter::INTBESTAND || $ancestor['type'] == DhastkImporter::INTVERZEICHNISEINHEIT): ?></strong><?php endif; ?>
                                </a>
                            </li>
                        <?php else: ?>
                        <?php endif ?>
                        <?php $i++ ?>
                    <?php endforeach ?>
                <?php endif ?>
                <?php if ($i > 0): ?>
                    <?php if ($dokument): ?>
                        <li>
                            <a href="<?php echo $dokument->getParentLink() ?>">
                                <?php echo $currentArchiv->getName() ?>
                            </a>
                            <span class="divider">&gt;</span>
                        </li>
                        <li>
                            <?php if ($dokument->getUsergenerated() == 0): ?><a
                                href="<?php echo url_for('@lesesaal_verzeichnungseinheit?id=' . $ve->getId() . '&slug=' . $ve->getSignaturSlug()) ?>"><?php endif ?>
                                <?php echo $ve->getTitel() ?>
                                <?php if ($dokument->getUsergenerated() == 0): ?></a><?php endif ?>
                            <span class="divider">/</span>
                        </li>
                        <?php if ($dokument->getUsergenerated() == 0): ?>
                            <li>
                                Datei <?php echo $dokument->getPosition() ?>
                            </li>
                        <?php endif ?>
                    <?php elseif ($ve): ?>
                        <li>
                            <a href="<?php echo url_for('@lesesaal?type=' . Archiv::getTypeSlug($currentArchiv->getType()) . '&id=' . $currentArchiv->id . '&slug=' . $currentArchiv->getSignaturSlug()) ?>">
                                <?php echo $currentArchiv->getName() ?>
                            </a>
                            <span class="divider">&gt;</span>
                        </li>
                        <li class="active">
                            <?php echo $ve->getTitel() ?>
                        </li>
                    <?php else: ?>
                        <li>
                            <?php echo $currentArchiv->getName() ?>
                        </li>
                    <?php endif ?>
                <?php else: ?>
                    <li>Willkommen im digitalen Lesesaal</li>
                <?php endif ?>
            </ul>
        </div>
    </div>
    <?php if ($ancestors): ?>
        <?php if ($sf_user->isAuthenticated() && isset($dontshowbookmark) === false):
        $bookmarkTitle = $currentArchiv->getMeinArchivTitle($ve);
        $type = ($ve?Bookmarks::TYPE_VE:Bookmarks::TYPE_ARCHIV);
        $tektonik_id = ($ve?$ve->getId():$currentArchiv->getId());
        $bookmark = Bookmarks::getBookmark($sf_user->getAttribute('user_id', null, 'sfGuardSecurityUser' ), $type, $tektonik_id);
        ?>
        <a class="save-bookmark tiptool<?php if(isset($bookmark->id)): ?> hide<?php endif ?>" title="<?php echo $bookmarkTitle ?> merken" data-mein-archiv-title="<?php echo $bookmarkTitle ?>" data-mein-archiv-bookmarkid="<?php if(isset($bookmark->id))echo $bookmark->id ?>" data-mein-archiv-type="<?php echo $type ?>" data-mein-archiv-id="<?php echo $tektonik_id ?>"><i class="icon-star-empty"></i></a>
        <a class="save-bookmark tiptool<?php if(isset($bookmark->id) === false): ?> hide<?php endif ?>" title="<?php echo $bookmarkTitle ?> nicht mehr merken" data-mein-archiv-title="<?php echo $bookmarkTitle ?>" data-mein-archiv-bookmarkid="<?php if(isset($bookmark->id))echo $bookmark->id ?>" data-mein-archiv-type="<?php echo $type ?>" data-mein-archiv-id="<?php echo $tektonik_id ?>"><i class="icon-star"></i></a>
        <?php endif ?>
    <?php endif ?>
</div>
<div class="chevronOverlay main"></div>
<div class="clearfix"></div>
