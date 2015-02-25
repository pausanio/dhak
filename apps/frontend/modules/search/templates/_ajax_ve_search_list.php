<div>
  <div id="aj_list">
    <?php
    use_helper('Number');
    $tekt_nr = $request->getParameter('tekt_nr');
    $bestand_sig = $request->getParameter('bestand_sig');
    foreach ($verzeinheiten as $ve) :
      $v_active = '';
      $get_ve_page = 1;
      $doc_count = HaVerzeinheiten::getDocumentCount($ve->getBestandSig(), $ve->getSignatur());
      $txt_dc = format_number($doc_count) . " ";
      $txt_dc .= ($doc_count === 1) ? __('Eintrag') : __('Einträge');
      ?>
      <li><a class="<?php echo $v_active; ?>" href="<?php echo url_for('archive/index?tekt_nr=' . $tekt_nr . '&bestand_sig=' . $bestand_sig . '&ve_sig=' . str_replace('/', '-', $ve->getSignatur()) . '&ve_page=' . $ve_page); ?>"><?php echo substr($ve->getTitel(), 0, 40); ?> (<?php echo $ve->getSignatur() ?>)
          <br/><?php echo $txt_dc; ?>
        </a></li>
    <?php endforeach ?>
  </div>
  <div id="aj_pager">
    <?php
    if ($ve_pager->haveToPaginate()) {
      include_partial('archive/ve_search_pager', array('ve_pager' => $ve_pager, 've_page' => $ve_page, 'tekt_nr' => $tekt_nr, 'bestand_sig' => $bestand_sig));
    }
    ?>
  </div>
</div>