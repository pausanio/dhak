<?php if (count($dokumente) > 0 || $dokument): ?>
    <?php if (isset($userDokumente) && $userDokumente): ?>
        <?php $updateUrl = url_for('@lesesaal?type=klassifikation&id=' . (int) $currentArchiv . '&slug=' . $signaturSlug) . '?'; ?>
    <?php else: ?>
        <?php $updateUrl = url_for('@lesesaal_verzeichnungseinheit?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $currentArchiv . '&slug=' . $verzeichnungseinheit->getSignaturSlug()) . '?'; ?>
    <?php endif; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#gallery_pages").change(function() {
                var url = '<?php echo $updateUrl . 'page='.$page.'&limit='; ?>';
                location.href = url + $("#gallery_pages option:selected").val();
            });
        });
    </script>
    <hr>
    <h2>
        <?php if ($dokument): ?>
            Datei <?php echo $dokument->getPosition() ?> von <?php echo $currentArchiv->getCountDocs() ?>
        <?php elseif (isset($userDokumente)): ?>
            Nicht zugeordnete nutzergenerierte Einträge (<?php echo $doc_total ?>)
        <?php else: ?>
            Dokumente (<?php echo $doc_total ?> Dateien)
        <?php endif ?>
    </h2>
    <?php if (isset($userDokumente)): ?>
        <?php echo cms_widget('dokument', 'hinweis_nutzergeneriert') ?>
    <?php endif ?>

    <?php $lang = $sf_user->getCulture() ?>
    <?php $images = array(1 => 'abschrift', 2 => 'digital', 3 => 'foto', 4 => 'kopie', 5 => 'mikrofilm', 6 => 'mikrofiche', 7 => 'online', 8 => 'druck', 9 => 'archivexemplar') ?>

    <div class="gallery">
        <?php if ($dokument === false): ?>
            <div class="filter">
                <div class="row-fluid">
                    <div class="span8">
                        <?php if ($doc_pager AND $doc_pager->haveToPaginate()): ?>
                            <div class="pagination pagination-small">
                                <ul>
                                    <li>
                                        <a href="<?php echo $updateUrl ?>limit=<?php echo $limit ?>&page=1">
                                            Erste Seite
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $updateUrl ?>limit=<?php echo $limit ?>&page=<?php echo $doc_pager->getPreviousPage() ?>">
                                            &lt;&lt;
                                        </a>
                                    </li>
                                    <?php foreach ($doc_pager->getLinks(4) as $page): ?>
                                        <li <?php echo ($page == $doc_pager->getPage()) ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo $updateUrl ?>limit=<?php echo $limit ?>&page=<?php echo $page ?>">
                                                <?php echo $page ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    <li>
                                        <a href="<?php echo $updateUrl ?>limit=<?php echo $limit ?>&page=<?php echo $doc_pager->getNextPage() ?>">
                                            &gt;&gt;
                                        </a>
                                    </li>
                                    <li>
                                        <a class="last" href="<?php echo $updateUrl ?>limit=<?php echo $limit ?>&page=<?php echo $doc_pager->getLastPage() ?>">
                                            Letzte Seite
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="span4">
                        <div class="pages text-right">
                            <select id="gallery_pages" name="gallery_pages" class="span4">
                                <?php foreach (array(12, 16, 24, 40) as $gallery_pages) : ?>
                                    <option <?php echo ($gallery_pages == $sf_user->getAttribute('gallery_pages')) ? ' selected="selected"' : ''; ?> value="<?php echo $gallery_pages ?>"><?php echo $gallery_pages ?></option>
                                <?php endforeach; ?>
                            </select>
                            pro Seite
                        </div>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="span12">
                        <?php $i = 1 ?>
                        <?php $doc_page = 0 ?>
                        <ul class="thumbnails">
                            <?php foreach ($content_data as $p => $item): ?>
                                <?php $alt = $item->getBestandSig() . ' - ' . $item->getSignatur() . ' | ' . $item->getPosition(); ?>
                                <li class="span3">
                                    <div class="thumbnail">
                                        <a href="<?php echo url_for('@lesesaal_dokument?sf_culture=' . $sf_user->getCulture() . '&id=' . (int) $item->getId() . '&slug=' . $item->getSignaturSlug()) ?>" target="_blank">
                                            <img class="doc" src="<?php echo $item->getThumb($sf_user->getCulture()) ?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>">
                                            <?php if ($item->getUsergenerated() == 1): ?><img class="icon" title="Dieses Dokument wurde von einem Nutzer hochgeladen." src="/images/icon_info.png" alt="Nutzer Upload" /><?php endif; ?>
                                            <p>
                                                <strong><?php echo $item->getBestandSig() ?> <?php echo $item->getSignatur() ?></strong>
                                            </p>
                                        </a>
                                        <div class="altlinks row-fluid">
                                                <div class="left">
                                                    <?php if ($item->getPdf()): ?>
                                                    <a class="tiptool "href="<?php echo url_for('document_pdf', array('bestand_sig' => Doctrine_Inflector::urlize($item->getBestandSig()), 'sig' => Doctrine_Inflector::urlize($item->getSignatur()), 'doc_id' => $item->getId())) ?>" target="_blank" title="<?php echo cms_widget('tooltip', 'Als PDF runterladen') ?>"><img src="/images/pdf.png" alt="PDF" /></a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="right" style="text-align:right">
                                                    <a class="tiptool" href="<?php echo $item->getDfgViewerLink($currentArchiv, $item->getPosition()) ?>" target="_blank" title="<?php echo cms_widget('tooltip', 'Im DFG Viewer anschauen') ?>"><img src="/images/logo_dfgviewer.gif" alt="DFG Viewer" /></a>
                                                </div>
                                            <div class="middle">
                                            <?php if(in_array($item->id, $myDoks)): ?>
                                                <span class="label label-info" title="In 'Mein Archiv' gespeichert">Mein Archiv</span>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif ?>
        </div>
    <?php endif; ?>
