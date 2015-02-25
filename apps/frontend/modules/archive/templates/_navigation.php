<?php
//die($navigation);
  use_helper('Number');
  $level = 0;
  $class = '';
if($navigation->count()>0): ?>
<ul>
<?php foreach($navigation as $nav) :

$li_active = ($tekt_nr[0] == $nav->getTektNr())? 'active' : '';
$a_open = (substr($tekt_nr,0,strlen($nav->getTektNr())) == $nav->getTektNr()) ? 'open' : '';

if($level == substr_count($nav->getTektNr(), '.')){
  echo '</li>';
}
if($level < substr_count($nav->getTektNr(), '.')){
  echo '<ul>';
}
if($level > substr_count($nav->getTektNr(), '.')){
  echo '</li></ul>';
}

$level = substr_count($nav->getTektNr(), '.');

$txt_vc = format_number($nav->getVeCount()). " ";
$txt_vc .= ($nav->getVeCount() ===1) ?  __('Verzeichnungseinheit') :  __('Verzeichnungseinheiten') ;
$txt_dc = format_number($nav->getDocCount()). " ";
$txt_dc .= ($nav->getDocCount() ===1) ?  __('Eintrag') :  __('Einträge') ;

?>
 <li class="<?php echo $li_active; ?>">
  <a class="<?php echo $a_open; ?>" href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig=0'); ?>"><strong><?php echo $nav->getTektNr(); ?>&nbsp;<?php echo $nav->getTektTitel(); ?></strong>
  <br><?php echo $txt_vc ?> | <?php echo $txt_dc ?>
  </a>
<?php if($tekt_nr==$nav->getTektNr() AND $nav->getHaBestand2()->count() >0 ) : ?>
  <ul>
  <?php foreach ($nav->getHaBestand2() as $best) : ?>
  <?php
    $b_active = ($bestand_sig == $best->getBestandSig())? 'active' : '';
    $b_active .= ($ve_pager AND $ve_pager->haveToPaginate())? ' noBorder' : '';
    
    $txt_vc = format_number($best->getHaVerzeinheiten()->count()). " ";
    $txt_vc .= ($best->getHaVerzeinheiten()->count() ===1) ?  __('Verzeichnungseinheit') :  __('Verzeichnungseinheiten') ;
    $txt_dc = format_number($best->getDocCount()). " ";
    $txt_dc .= ($best->getDocCount() ==="1") ?  __('Eintrag') :  __('Einträge') ;

  ?>
    <li>
     <a class="<?php echo $b_active; ?>" href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig='.$best->getBestandSig()); ?>"><strong><?php echo $best->getBestandsname(); ?></strong> (<?php echo $best->getBestandSig(); ?>)
       <br><?php echo $txt_vc; ?> | <?php echo $txt_dc;?>
     </a>
     <?php if($bestand_sig==$best->getBestandSig() AND $verzeinheiten->count()>0) :?>
     <ul>

<?php if ($ve_pager->count() >10) :?>
<div class="filterbox">
  <form id="ve_search_form" method="get" action="">
    <div class="view">Verzeichn.-Einheiten:
      <span>
        <a class="<?php echo ($ve_search_count==10)? 'active' : '';  ?>" href="<?php echo url_for('archive/index?ve_search_count=10'); ?>">10</a> |
        <a class="<?php echo ($ve_search_count==20)? 'active' : '';  ?>" href="<?php echo url_for('archive/index?ve_search_count=20'); ?>">20</a> |
        <a class="<?php echo ($ve_search_count==50)? 'active' : '';  ?>" href="<?php echo url_for('archive/index?ve_search_count=50'); ?>">50</a>
        <a class="<?php echo ($ve_search_count==10000)? 'active' : '';  ?>" href="<?php echo url_for('archive/index?ve_search_count=10000'); ?>">Alle</a>
      </span>
    </div>
    <div class="field">
    <form>
      <input id="vesearch" type="text" class="text" name="ve_search"   />
      <input type="submit" class="submit" value="Filter" />
    </form>
      <div class="fixfloat"></div>
    </div>
    <div id="paging" class="paging">
    <?php if($ve_pager->haveToPaginate()) {
            include_partial('ve_search_pager', array('ve_pager'=>$ve_pager, 've_page'=>$ve_page, 'tekt_nr'=>$nav->getTektNr(), 'bestand_sig'=>$best->getBestandSig())); }
    ?>
    </div>
  </form>
</div>
<div id="filterboxresults"></div>
<?php endif ?>
      <span id="ve_list">
     <?php foreach ($verzeinheiten as $ve) :
        $v_active = ($ve_sig == $ve->getSignatur())? "active noArrow" : '';
        $get_ve_page = ($ve_page>1)? '&ve_page='.$ve_page : '';
        $txt_dc = format_number($ve->getHaDocuments()->count()). " ";
        $txt_dc .= ($ve->getHaDocuments()->count() ==="1") ?  __('Eintrag') :  __('Einträge') ;
     ?>
     <li><a class="<?php echo $v_active; ?>" href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig='.$bestand_sig.'&ve_sig='.str_replace('/','-',$ve->getSignatur()).$get_ve_page); ?>"><?php echo substr($ve->getTitel(),0,40); ?> (<?php echo $ve->getSignatur() ?>)
        <br/><?php echo $txt_dc; ?>
     </a></li>
     <?php endforeach ?>
     </span>
     </ul>
     
     <?php endif ?>
    </li>
  <?php endforeach ?>
  </ul>
  <?php endif;  ?>
  
  <?php endforeach; ?>
</ul>
<?php endif; ?>  