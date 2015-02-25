<script type="text/javascript">
$(function(){
return;
 $("#navArchive a").click(function(event){
     event.preventDefault();
      var a = $(event.target);
      $("#navArchive li.active").removeClass('active');
      a.parents("li").addClass('active');
//a.parents("li").append().load($(this).attr('href'));
//$.(load($(this).attr('href'))).appendTo($("#navArchive li.active"));
//a.parents("li").append('<hr>');

$.get($(this).attr('href'), function(data) {
  $("#navArchive li.active").append(data);
});

      
//a.parents("li").append($.(load($(this).attr('href'))));
//a.append().load($(this).attr('href'));
//$('#ajax').load($(this).attr('href'));


//      $('#dia').load(
//       '<?php #echo url_for('mailform') ?>',
//       { type: 'project',id: a.attr('projectID')}
//      );

  });
});    
</script>
<div id="ajax"></div>
<div id="archiveWrapper" class="">
 <div class="header">
  <div class="viewType"></div>
  <div class="viewOptions"></div>
 </div>
 <div class="sidebar ">
  <div class="sidebarWrapper">
<?php
  $level = 0;
  $class = '';
  //subActive

?>
<ul id="navArchive" class="">
<?php foreach($navigation as $nav) :
$li_active = ($tekt_nr == $nav->getTektNr())? 'active' : '';

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

?>
 <li class="<?php echo $li_active; ?>">
  <a href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig=0'); ?>"><strong><?php echo $nav->getTektTitel(); ?></strong></a>
<?php if($tekt_nr==$nav->getTektNr() AND $nav->getHaBestand2()->count() >0 ) : ?>
  <ul>
  <?php foreach ($nav->getHaBestand2() as $best) : ?>
    <li>
     <a class="active" href="<?php echo url_for('archive/index?tekt_nr='.$nav->getTektNr().'&bestand_sig='.$best->getBestandSig()); ?>"><?php echo $best->getBestandsname(); ?> (<?php echo $best->getBestandSig(); ?>)</a>
     <?php if($bestand_sig==$best->getBestandSig() AND $verzeinheiten->count()>0) :?>
     <ul>
     <?php foreach ($verzeinheiten as $ve) : ?>
     <li><a href=""><?php echo substr($ve->getTitel(),0,40); ?></a></li>
     <?php endforeach ?>
     </ul>
     <?php endif ?>
    </li>
  <?php endforeach ?>
  </ul>
  <?php endif;  ?>
<!--</li>-->
<?php endforeach; ?>
</ul>

  </div>
</div>
<div class="content">

</div>
  <div class="footerSpacer"></div>
  <div class="footer">
  </div>
</div>