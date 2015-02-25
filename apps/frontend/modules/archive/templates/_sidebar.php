<?php
$level = 0;
$class = '';
//subActive
?>
<ul id="navArchive" class="">
  <?php
  foreach ($navigation as $nav) :

    $li_active = ($tekt_nr[0] == $nav->getTektNr()) ? 'active' : '';
    $a_open = (substr($tekt_nr, 0, strlen($nav->getTektNr())) == $nav->getTektNr()) ? 'open' : '';

    if ($level == substr_count($nav->getTektNr(), '.')) {
      echo '</li>';
    }
    if ($level < substr_count($nav->getTektNr(), '.')) {
      echo '<ul>';
    }
    if ($level > substr_count($nav->getTektNr(), '.')) {
      echo '</li></ul>';
    }

    $level = substr_count($nav->getTektNr(), '.');
    ?>
    <li class="<?php echo $li_active; ?>">
      <a class="<?php echo $a_open; ?>" href="<?php echo url_for('archive/index?tekt_nr=' . $nav->getTektNr() . '&bestand_sig=0'); ?>"><strong><?php echo $nav->getTektNr(); ?>&nbsp;<?php echo $nav->getTektTitel(); ?></strong></a>
      <?php if ($tekt_nr == $nav->getTektNr() AND $nav->getHaBestand2()->count() > 0) : ?>
        <ul>
          <?php foreach ($nav->getHaBestand2() as $best) : ?>
            <?php
            $b_active = ($bestand_sig == $best->getBestandSig()) ? 'active' : '';
            $b_active .= ($ve_pager AND $ve_pager->haveToPaginate()) ? ' noBorder' : '';
            ?>
            <li>
              <a class="<?php echo $b_active; ?>" href="<?php echo url_for('archive/index?tekt_nr=' . $nav->getTektNr() . '&bestand_sig=' . urlencode(urlencode($best->getBestandSig()))); ?>"><strong><?php echo $best->getBestandsname(); ?></strong> (<?php echo $best->getBestandSig(); ?>)</a>
              <?php if ($bestand_sig == $best->getBestandSig() AND $verzeinheiten->count() > 0) : ?>
                <ul>
                  <?php
                  if ($ve_pager->haveToPaginate()) {
                    include_partial('ve_search', array('pager' => $ve_pager, 'page' => $ve_page, 'tekt_nr' => $nav->getTektNr(), 'bestand_sig' => $best->getBestandSig()));
                  }
                  ?>
                  <?php
                  foreach ($verzeinheiten as $ve) :
                    $get_ve_page = ($ve_page > 1) ? '&ve_page=' . $ve_page : '';
                    ?>
                    <li><a href="<?php echo url_for('archive/index?tekt_nr=' . $nav->getTektNr() . '&bestand_sig=' . $bestand_sig . '&ve_sig=' . $ve->getSignatur() . $get_ve_page); ?>"><?php echo substr($ve->getTitel(), 0, 40); ?> (Sig. <?php echo $ve->getSignatur() ?>)</a></li>
                  <?php endforeach ?>
                </ul>
              <?php endif ?>
            </li>
          <?php endforeach ?>
        </ul>
      <?php endif; ?>
      <!--</li>-->
    <?php endforeach; ?>
</ul>
