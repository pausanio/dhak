<?php if (count($userDokumente) > 0): ?>
<?php include_partial('archiv/dokumente', array('currentArchiv' => $currentArchiv->id,
                                                'dokument' => $dokument, 
                                                'dokumente' =>  $userDokumente,
                                                'userDokumente' => true,
                                                'doc_total' => $doc_total,
                                                'page' => $page,
                                                'limit' => $limit,
                                                'signaturSlug' => $currentArchiv->getSignaturSlug(),
                                                'content_data' => $content_data,
                                                'doc_pager' => $doc_pager,
                                                'myDoks' => (isset($myDoks)?$myDoks:array()))) ?>
<?php endif; ?>