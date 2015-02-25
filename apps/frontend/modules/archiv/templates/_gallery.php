<?php $lang = $sf_user->getCulture() ?>
<?php $images = array(1 => 'abschrift', 2 => 'digital', 3 => 'foto', 4 => 'kopie', 5 => 'mikrofilm', 6 => 'mikrofiche', 7 => 'online', 8 => 'druck', 9 => 'archivexemplar'); ?>
<div class="gallery clearfix">
    <?php if ($doc_pager AND $doc_pager->haveToPaginate()): ?>
        <?php $get_ve_page = ($ve_page > 1) ? '&ve_page=' . $ve_page : ''; ?>
        <?php $ve_sig = str_replace('/', '-', $ve_sig); ?>
        <div class="paging">
            <a class="first" href="?page=1">Erste Seite</a>
            <a href="?page=<?php echo $doc_pager->getPreviousPage() ?>" class="prev">Seite zurück</a>
            <ul class="pages">
                <?php foreach ($doc_pager->getLinks() as $page): ?>
                    <li>
                        <a <?php echo ($page == $doc_pager->getPage()) ? 'class="active"' : ''; ?> href="?page=<?php echo $page ?>"><?php echo $page ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="?page=<?php echo $doc_pager->getNextPage() ?>" class="next">Nächste Seite</a>
            <a class="last" href="?page=<?php echo $doc_pager->getLastPage() ?>">Letzte Seite</a>
        </div>
    <?php endif; ?>
    <div class="topic">
        <select name="topic">
            <option>Alle Überlieferungen</option>
        </select>
    </div>
    <div class="pages">
        <select id="gallery_pages" name="gallery_pages">
            <?php foreach (array(20, 50, 100, 250, 500) as $gallery_pages) : ?>
                <option <?php echo ($gallery_pages == $sf_user->getAttribute('gallery_pages')) ? ' selected="selected"' : ''; ?> value="<?php echo $gallery_pages ?>"><?php echo $gallery_pages ?></option>
            <?php endforeach; ?>
        </select>
        pro Seite
    </div>
</div>
