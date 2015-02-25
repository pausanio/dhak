<?php use_javascript('http://ajax.cdnjs.com/ajax/libs/underscore.js/1.5.2/underscore-min.js') ?>
<?php use_javascript('http://ajax.cdnjs.com/ajax/libs/backbone.js/1.0.0/backbone-min.js') ?>
<?php use_javascript('/javascripts/frontend/viewer/viewer.js') ?>
<?php use_javascript('/javascripts/lib/ui.anglepicker.js') ?>
<?php use_javascript('/javascripts/lib/jquery.cookies.js') ?>
<?php use_javascript('/javascripts/lib/jquery.blockui.min.js') ?>
<?php use_javascript('/javascripts/lib/bootstrap.flash.js') ?>

<div id="viewer_container">
    <div class="row-fluid">
        <div class="navbar">
            <div class="navbar-inner">
                <span class="brand">
                    <a href="<?php echo $dokument->getParentLink(); ?>">
                        <?php echo $sf_request->getAttribute('current_signatur') ?>
                    </a>
                </span>

                <div class="span7">
                    <a class="close_signatur btn btn-mini" href="#">Signatur schließen</a>
                    <?php include_component('archiv', 'DokumentBreadcrumb') ?>
                </div>
                
                <?php if ($dokument->getPdf()): ?>
                        <div class="pull-right pdf-icon">
                            <a href="<?php echo url_for('document_pdf', array('bestand_sig' => Doctrine_Inflector::urlize($dokument->getBestandSig()), 'sig' => Doctrine_Inflector::urlize($dokument->getSignatur()), 'doc_id' => $dokument->getId())) ?>"
                               class="tiptool" target="_blank"
                               title="<?php echo cms_widget('tooltip', 'Als PDF anschauen') ?>"><img
                                    src="/images/pdf.png" alt="PDF"/></a>
                        </div>
                    <?php endif ?>
                    <?php
                    $dfgViewerDokId = $currentArchiv->getId();
                    if ($verzeichnungseinheit) {
                        $dfgViewerDokId = $verzeichnungseinheit->getId();
                    }
                    ?>
                    <div class="pull-right dfg-icon">
                        <a href="<?php echo $dokument->getDfgViewerLink($dfgViewerDokId, $dokument->getPosition()) ?>"
                           class="tiptool"
                           target="_blank" title="<?php echo cms_widget('tooltip', 'Im DFG Viewer anschauen') ?>">
                            <img src="/images/logo_dfgviewer.gif" alt="DFG Viewer"/>
                        </a>
                    </div>
                
                <?php if ($infopage): ?>
                    <ul class="nav pull-right">
                        <li class="nav pull-right">
                            <a href="#LeseUndArbeitshilfen" data-toggle="modal">
                                <?php echo $infopage->getTitle() ?>
                            </a>
                        </li>
                    </ul>
                <?php endif ?>
                 
            </div>
        </div>
    </div>

    <div class="row-fluid">

        <div class="span12" id="viewer_viewer">
            <div id="viewer"
                 data-viewer-bestandsignatur="<?php echo Doctrine_Inflector::urlize($dokument->bestand_sig) ?>"
                 data-viewer-signatur="<?php echo $dokument->getSignaturSlug() ?>"
                 data-viewer-image="<?php echo $dokument->getImageBaseName() ?>"
                 data-viewer-veid="<?php echo $dokument->getVerzeichnungseinheitId() ?>"
                 data-viewer-dokumentid="<?php echo $dokument->getId() ?>"
                 data-viewer-usergenerated="<?php echo $dokument->getUsergenerated() ?>"
                 data-viewer-user="<?php echo($sf_user->getGuardUser() ? $sf_user->getGuardUser()->getId() : ''); ?>"></div>
            <ul class="nav nav-tabs" id="viewer-sidebar-tabs">
                <li class="toggle_signature tab_signature">
                    <a href="#signature">zu dieser Signatur
                        <?php if($myVE): ?>
                            <span class="removeFromBookmark icon-star tiptool" title="<?php echo cms_widget('lesesaal', 'Diese Signatur haben Sie bereits gespeichert.') ?>"></span>
                            <span class="saveAsBookmark icon-star-empty tiptool hide" title="<?php echo cms_widget('lesesaal', 'Signatureinstellungen speichern') ?>"></span>
                        <?php else: ?>
                            <span class="removeFromBookmark icon-star tiptool hide" title="<?php echo cms_widget('lesesaal', 'Diese Signatur haben Sie bereits gespeichert.') ?>"></span>
                            <span class="saveAsBookmark icon-star-empty tiptool" title="<?php echo cms_widget('lesesaal', 'Signatureinstellungen speichern') ?>"></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="toggle_image tab_image">
                    <a href="#image">zu dieser Bildansicht
                        <?php if($myDokument): ?>
                            <span class="removeFromBookmark icon-star tiptool" title="<?php echo cms_widget('lesesaal', 'Diese Bildansicht haben Sie bereits gespeichert.') ?>"></span>
                            <span class="saveAsBookmark icon-star-empty tiptool hide" title="<?php echo cms_widget('lesesaal', 'Bildeinstellungen speichern') ?>"></span>
                        <?php else: ?>
                            <span class="removeFromBookmark icon-star tiptool hide" title="<?php echo cms_widget('lesesaal', 'Diese Bildansicht haben Sie bereits gespeichert.') ?>"></span>
                            <span class="saveAsBookmark icon-star-empty tiptool" title="<?php echo cms_widget('lesesaal', 'Bildeinstellungen speichern') ?>"></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
         </div>

        <div id="viewer-sidebar" class="tab_signature">
            <div class="close-sidebar icon-remove tiptool" title="Seitenleiste schließen"></div>
            <div class="tab-content">
                <div class="tab-pane active" id="signature">
                    <?php include_partial('viewer/sidebarSignature', array('verzeichnungseinheit' => $verzeichnungseinheit, 'dokument' => $dokument, 'currentArchiv' => $currentArchiv, 'myVE' => $myVE, 'myDokument' => $myDokument)) ?>
                </div>
                <div class="tab-pane" id="image">
                    <?php include_partial('viewer/sidebarImage', array('verzeichnungseinheit' => $verzeichnungseinheit, 'dokument' => $dokument, 'currentArchiv' => $currentArchiv, 'myVE' => $myVE, 'myDokument' => $myDokument)) ?>
                </div>
                <div class="spacer">
                    <?php echo cms_widget('lesesaal', 'viewer_annotations_beta', 'alert alert-info') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="hide" id="saveSignatureText"
         data-saved-text="<?php echo cms_widget('lesesaal', 'Signatureinstellungen wurden gespeichert') ?>"></div>
    <div class="hide" id="saveImageText"
         data-saved-text="<?php echo cms_widget('lesesaal', 'Bildeinstellungen wurden gespeichert') ?>"></div>
    <div class="hide" id="removeSignatureText"
         data-removed-text="<?php echo cms_widget('lesesaal', "Die gemerkte Signatur wurde aus 'Mein Archiv' gelöscht.") ?>"></div>
    <div class="hide" id="removeImageText"
         data-removed-text="<?php echo cms_widget('lesesaal', "Die gemerkte Bildeinstellung wurde aus 'Mein Archiv' gelöscht.") ?>"></div>

    <?php if ($infopage): ?>
        <div id="LeseUndArbeitshilfen" class="modal hide fade" role="dialog">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?php echo $infopage->getTitle() ?></h3>
            </div>
            <div class="modal-body">
                <?php echo htmlspecialchars_decode($infopage->getText()); ?>
            </div>
        </div>
    <?php include_partial('default/confirmDialog', array('idSuffix' => 'VE', 'confirmTitle' => cms_widget('lesesaal', 'Gemerkte Signatur löschen?'),'confirmText' => cms_widget('lesesaal', 'Wollen Sie diese Signatur aus "Mein Archiv" löschen?'))) ?>
    <?php include_partial('default/confirmDialog', array('idSuffix' => 'Dokument', 'confirmTitle' => cms_widget('lesesaal', 'Gemerkte Bildansicht löschen?'),'confirmText' => cms_widget('lesesaal', 'Wollen Sie diese Bildansicht aus "Mein Archiv" löschen?'))) ?>
    <?php endif ?>
    <?php
    $imgDimensions = $dokument->getImageDimensions();
    if ($imgDimensions) {
        $imgWidth = $imgDimensions[0];
        $imgHeight = $imgDimensions[1];
    } else {
        $imgWidth = 3000;
        $imgHeight = 3000;
    }
    ?>
    <script>
        $(document).ready(function () {
            var paging = false;
            $('.pulldown, .pullup, .removeFromBookmark, .saveAsBookmark, .close-sidebar, span.icon-remove.tiptool').tooltip();
            $(".pulldown").click(function () {
                $(this).hide();
                $('.pullup').show().attr('title', 'Header einklappen');
                $("#miniheader_container").hide();
                $("#header_container").show();
            });
            $(".pullup").click(function () {
                $(this).hide();
                $('.pulldown').show().attr('title', 'Header ausklappen');
                $("#miniheader_container").show();
                $("#header_container").hide();
            });
            $(".close_signatur").click(function (e) {
                e.preventDefault();
                if (confirm("Wollen Sie dieses Fenster wirklich schließen?")) {
                    close();
                }
            });

            <?php if ($verzeichnungseinheit):
                    $parentId = $verzeichnungseinheit->getId();
                    $parentType = 've';
                  else:
                    $parentId = $dokument->getArchivId();
                    $parentType = 'archiv';
                  endif;
                 $docRoutes = get_component('viewer', 'routes', array('parentId' => $parentId, 'parentType' => $parentType, 'currentId' => $dokument->getId()));
            ?>
            var routes = <?php echo $docRoutes ?>,
                current = <?php echo ($dokument->getPosition()?$dokument->getPosition():1)?>;
            <?php if (!$verzeichnungseinheit):?>
            current = routes.current;
            <?php endif; ?>
            paging = {current: current,
                total: routes.routes.length, //this we could calc in the viewer
                routes: routes.routes};
            <?php
            $hideMap = false;
            $extendToolbar = false;
            $collapseToolbar = false;
            $sidebar = false;
            $manipulations = false;
            if($uiSettings):
                $manipulations = json_encode($uiSettings->manipulations);
                if($uiSettings->toolbar->hideMap){
                    $hideMap = 'true';
                }
                if($uiSettings->toolbar->collapseToolbar){
                    $collapseToolbar = 'true';
                }
                if($uiSettings->toolbar->extendToolbar){
                    $extendToolbar = 'true';
                }
            endif;
            ?>
            var settings = {
                'mainImgWidth': <?php echo $imgWidth; ?>,
                'mainImgHeight': <?php echo $imgHeight; ?>,
                'previewImgFilename': '<?php echo $dokument->getOrgImage($sf_user->getCulture()) ?>',
                'mapThumbFilename': '<?php echo $dokument->getThumb($sf_user->getCulture()) ?>',
                'bookmarks':{
                    'signature': <?php if($myVE): ?><?php echo $myVE->id ?><?php else: ?>null<?php endif ?>,
                    'image': <?php if($myDokument): ?><?php echo $myDokument->id ?><?php else: ?>null<?php endif ?>
                },
                'paging': paging,
                <?php if ($manipulations): ?>'manipulations': <?php echo $manipulations ?>, <?php endif ?>
                <?php if ($hideMap): ?>'hideMap': <?php echo $hideMap ?>, <?php endif ?>
                <?php if ($extendToolbar): ?>'extendToolbar': <?php echo $extendToolbar ?>, <?php endif ?>
                <?php if ($collapseToolbar): ?>'collapseToolbar': <?php echo $collapseToolbar ?>, <?php endif ?>
                <?php if ($sidebar): ?>'sidebar': <?php echo $sidebar ?>, <?php endif ?>
            };
            var viewer = new Viewer(settings);
            viewer.render();
        });
    </script>
