<?php
$ve_search_attr = $sf_user->getAttribute('ve_search');

$ve_search_count = $ve_search_attr['count'];
$ve_search_page = $ve_search_attr['page'];
$ve_search_term = $ve_search_attr['term'];
?>

<?php if(!$sf_request->isXmlHttpRequest()) :?>
<script type="text/javascript">

$(document).ready(function(){
var ve_count = <?php echo $ve_search_count;?>;
var ve_page = <?php echo $ve_search_page;?>;
var ve_term = '<?php echo $ve_search_term;?>';

//NAVIGATION

$('ul#navArchive a').live("click", function(e) {
return;
  e.preventDefault();
  var a = $(e.target);
  $("#navArchive li.active").removeClass('active');
  a.parents("li").addClass('active');
  if(a.closest("li").children("ul").length==0){
      $.get($(this).attr('href'), function(data) {
      a.closest("li").append(data);
      //a.closest("li").append($(data).find('li > ul'));
    });
  } else {
    a.closest("li").children("ul").toggle();
  }
});

//VE-SUCHBOX
$('div.view a').click(function(e) {
  e.preventDefault();
  ve_count = $(this).text();
  $('div.view a.active').removeClass('active');
  $(this).addClass('active');
  $('#ve_search_form').submit();
});

$('div#paging a').live("click", function(e) {
  e.preventDefault();
  ve_page = $(this).text();
  $('div#paging a.strong').removeClass('strong');
  $(this).addClass('strong');
  $('#ve_search_form').submit();
});

$('#ve_search_form').submit(function(e) {
  e.preventDefault();
  var $q = $('#vesearch');
  if($q.val()!=ve_term){
    ve_page = 1;
  }
  $('span#ve_list').hide(1000);
  $('div#filterboxresults').toggle(700, 'linear', function() {
        $.get('<?php echo url_for('search/index?topic=VerzeinheitenByBestand&tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig); ?>',
        { term:$q.val(), count: ve_count, page: ve_page
        },function(data){
        $('div#filterboxresults').html($(data).find('#aj_list'));
        $('div#paging').html($(data).find('#aj_pager'));
          $('div#filterboxresults').toggle(700);
        }, 'html');
      });
  });

});

</script>
<?php endif ;?>

<?php
  use_helper('Number');
  $level = 0;
  $class = '';
  //subActive



?>
<ul id="navArchive" class="">
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
  <?php
  //print_r($nav->getHaBestand2()->toArray());//exit;
  foreach ($nav->getHaBestand2() as $best) : ?>
  <?php
    $b_active = ($bestand_sig == $best->getBestandSig())? 'active' : '';
    $b_active .= ($ve_pager AND $ve_pager->haveToPaginate())? ' noBorder' : '';
    
    $txt_vc = format_number($best->getHaVerzeinheiten()->count()). " ";
    $txt_vc .= ($best->getHaVerzeinheiten()->count() ===1) ?  __('Verzeichnungseinheit') :  __('Verzeichnungseinheiten') ;
    $txt_dc = format_number($best->getDocCount()). " ";
    //var_dump($best->getDocCount());die();
    $txt_dc .= ($best->getDocCount() === "1") ?  __('Eintrag') :  __('Einträge') ;

  ?>
    <li>
     <a class="<?php echo $b_active; ?>" href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig='.urlencode($best->getBestandSig())); ?>"><strong><?php echo $best->getBestandsname(); ?></strong> (<?php echo $best->getBestandSig(); ?>)
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
        //$doc_count = $ve->getHaDocuments()->count();
        $doc_count = HaVerzeinheiten::getDocumentCount($ve->getBestandSig(), $ve->getSignatur());
        //$doc_count = 'xxx';
        $txt_dc = format_number($doc_count). " ";
        $txt_dc .= ($doc_count ==="1") ?  __('Eintrag') :  __('Einträge') ;
//         $txt_dc = format_number('xx'). " ";
//         $txt_dc .= (2 ===1) ?  __('Eintrag') :  __('Einträge') ;
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
