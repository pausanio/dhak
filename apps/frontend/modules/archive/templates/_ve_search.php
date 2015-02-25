<script type="text/javascript">
$(document).ready(function(){
  var runningRequest = false;
    var request;
   //Identify the typing action
    $('#vesearch').keyup(function(e){
    $('#ve_list').hide();
        e.preventDefault();
        var $q = $(this);

        if($q.val() == ''){
            $('div#filterboxresults').html($q.val());
            $('#ve_list').show();
            $('div#filterboxresults').hide();
            return false;
        }

        //Abort opened requests to speed it up
        if(runningRequest){
            request.abort();
        }
        if($q.val().length > 2){
          runningRequest=true;
          request = $.get('<?php echo url_for('search/index?topic=VerzeinheitenByBestand&tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig); ?>',{
              term:$q.val()
          },function(data){
              showResults(data,$q.val());
              runningRequest=false;
          });
        }
//Create HTML structure for the results and insert it on the result div
  function showResults(data, highlight){
           var resultHtml = data;
            $('div#filterboxresults').html(resultHtml);
            $('div#filterboxresults').show();
        }

        $('form').submit(function(e){
            e.preventDefault();
        });
  });
});

</script>

<div class="filterbox">
<?php
$ve_search_attr = $sf_user->getAttribute('ve_search');
$ve_search_count = $ve_search_attr['count'];
?>
  <form method="get" action="">
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
    <div class="paging">
      <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$ve_pager->getPreviousPage()) ?>" class="prev">
      <?php echo __('Vorherige') ?>
      </a>
      <div class="pages">
    <?php foreach ($ve_pager->getLinks() as $page): ?>
      <?php if ($page == $ve_pager->getPage()): ?>
        <strong><?php echo $page ?></strong>
      <?php else: ?>
      <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$page) ?>"><?php echo $page; ?></a>
      <?php endif; ?>
    <?php endforeach; ?>      
      </div>
      <a href="<?php echo url_for('archive/index?tekt_nr='.$tekt_nr.'&bestand_sig='.$bestand_sig.'&ve_page='.$ve_pager->getNextPage()) ?>" class="next">
      <?php echo __('Nächste') ?>
      </a>
    </div>
  </form>
</div>
<div id="filterboxresults"></div>