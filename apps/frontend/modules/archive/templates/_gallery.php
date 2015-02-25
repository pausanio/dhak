<script type="text/javascript">   
   $(document).ready(function() {
   $("#gallery_pages").change(function() {
   var url='<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.str_replace('/','-',$ve_sig)); ?>?limit=';
     location.href = url+$("#gallery_pages option:selected").val();
   });
   });
</script>
<?php
$lang = $sf_user->getCulture();
$images = array(1=>'abschrift',2=>'digital',3=>'foto',4=>'kopie',5=>'mikrofilm',6=>'mikrofiche',7=>'online',8=>'druck',9=>'archivexemplar');
?>
<div class="gallery">
    
<?php if($ve_sig!='0') : ?>
    <div class="filter">
<?php
if ($doc_pager AND $doc_pager->haveToPaginate()):
//$doc_pager->getResults();
  $get_ve_page = ($ve_page>1)? '&ve_page='.$ve_page : '';
  //print_r($doc_pager->getLinks()); exit;
  $ve_sig = str_replace('/','-',$ve_sig);
?>
   <div class="paging">
    <a  class="first" href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.$ve_sig); ?>?page=1<?php echo $get_ve_page?>">Erste Seite</a>
    <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.$ve_sig); ?>?page=<?php echo $doc_pager->getPreviousPage() ?>" class="prev">Seite zurück</a>
    <ul class="pages">
    <?php foreach ($doc_pager->getLinks() as $page): ?>
      <li>
         <a <?php echo ($page == $doc_pager->getPage())? 'class="active"':''; ?> href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.$ve_sig); ?>?page=<?php echo $page ?><?php echo $get_ve_page?>"><?php echo $page ?></a>
      </li>
    <?php endforeach; ?>
    </ul>
    <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.$ve_sig); ?>?page=<?php echo $doc_pager->getNextPage() ?>" class="next">Nächste Seite</a>
    <a  class="last" href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_sig='.$ve_sig); ?>?page=<?php echo $doc_pager->getLastPage() ?><?php echo $get_ve_page?>">Letzte Seite</a>
    </div>
<?php endif; ?>
      <div class="topic">
        <select name="topic">
          <option>Alle Überlieferungen</option>
        </select>
      </div>
      <div class="pages">
        <select id="gallery_pages" name="gallery_pages">
        <?php foreach (array(20,50,100,250,500) as $gallery_pages) :?>
          <option <?php echo ($gallery_pages == $sf_user->getAttribute('gallery_pages'))? ' selected="selected"':''; ?> value="<?php echo $gallery_pages ?>"><?php echo $gallery_pages ?></option>
          <?php endforeach; ?>
        </select>
        pro Seite
      </div>

      <div class="fixfloat"></div>
  </div>
<?php endif; // vesig > 0?>   
<?php
$i = 1;
$doc_page = 0;
foreach($content_data as $p=>$item) :?>
<?php
$class = ($i%3==0)? 'item no-margin-right' : '';
if($item->getIntname() AND in_array(strtolower(substr($item->getIntname(), -4)), array('.jpg', '.png', '.gif'))){
  $doc_page++;
  $thumb = '/images/documents/thumbs/th_' . $item->getIntname();
} else {
  $thumb = '/images/liegt_vor_'.$images[$item->getVorlageId()].'_'.$sf_user->getCulture().'.png';
}

if($ve_sig!='0'){
  $link = url_for('lesesaal_show', array(
                        'tekt_nr'=>$item->getHaVerzeinheiten()->getHaBestand2()->getTektNr(),
                        'bestand_sig'=>$item->getHaVerzeinheiten()->getHaBestand2()->getBestandSig(),
                        've_sig'=>str_replace('/','-',$item->getHaVerzeinheiten()->getSignatur()),
                        'doc_id'=>$item->getId(),'doc_page'=>$doc_page)
                        );
  $target = '';
} else {
  $link = url_for('lesesaal', array(
                        'tekt_nr'=>$item->getHaVerzeinheiten()->getHaBestand2()->getTektNr(),
                        'bestand_sig'=>$item->getHaVerzeinheiten()->getHaBestand2()->getBestandSig(),
                        've_sig'=>str_replace('/','-',$item->getHaVerzeinheiten()->getSignatur())));
  $target = '';
}


if ($bestand_sig == '0' AND $ve_sig == '0'){ //Ebene Tektonik
    $title = $item->getHaVerzeinheiten()->getHaBestand2()->getBestandsname();
    $text = $item->getHaVerzeinheiten()->getHaBestand2()->getBestandSig();
    $alt = $title;
}

if ($bestand_sig != '0' AND $ve_sig == '0'){ //Ebene Bestand
    $title = $item->getSignatur();
    $text = $item->getHaVerzeinheiten()->getTitel();
    $alt = $text;
  }


if ($ve_sig != '0'){ //Ebene VE
    $title = $item->getTitleDe();
    //$text = __('Seite').' '.($p+1+($doc_pager->getPage()*9)-9);
    $text = $item->getFolio();
    $alt = $title;
}


?>
  <div class="item <?php echo $class; ?>">
    <a href="<?php echo $link; ?>" class="prev"<?php echo $target; ?>>
      <img src="<?php echo $thumb?>" title="<?php echo $alt; ?>" alt="<?php echo $alt; ?>">
    </a>
    <h4><?php echo $title ?></h4>
    <p><?php echo $text ;?></p>
  </div>

<?php
$i++;
endforeach ?>
      <div class="fixfloat"></div>

</div>
