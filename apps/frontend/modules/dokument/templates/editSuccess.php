
<h2 class="form">Archivalie hochladen</h2>

<div id="archiveWrapper" class="clearfix no-header">
  <div class="sidebar">
    <div id="archivSelect" class="btn btn-inactive">Auswahl der Archivsystematik:</div>
    <div id="loadingTree"><span>Tektonik wird geladen...</span></div>
    <div id="archiveTree"></div>
  </div>
  <div class="content archiv">
    <?php include_partial('form', array('form' => $form, 'archiv_title' => $archiv_title, 'verzeichnungseinheiten' => $verzeichnungseinheiten, 'selected_ve' => $selected_ve)) ?>
  </div>
</div>

<script>
  $(document).ready(function() {
  });
</script>

<script>
  $('#archiveTree').load('/index.php/meine-archivalien/tree?id=<?php echo $archiv_id ?>', function(response, status, xhr) {
    if (status == 'error') {
      var msg = 'Fehler: : ';
      $('#loadingTree').html(msg + xhr.status + " " + xhr.statusText);
    }
    if (status == 'success') {
      $('#loadingTree').hide();
      $('ul#navArchive li a.link').bind('click', function(e) {
        e.preventDefault();
        updateTree($(this).attr('data-id'));
        $('td.archiv_title').html($(this).children('b:first').html());
        $('#dokument_archiv_id').val($(this).attr('data-id'));
        var ArchivTitle = $('#archiveTree a.active b').html();
        $('#archivTitle').html(ArchivTitle);
        getVerzeichnungseinheiten(<?php echo $archiv_id ?>);
      });
    }
  });

  $('#verzeichnungseinheit_id').change(function()
  {
    $('#dokument_verzeichnungseinheit_id').val( $(this).attr('value') );
  });

  function updateTree(id)
  {
    $('#loadingTree').show();
    $('#archiveTree').load('/index.php/meine-archivalien/tree?id=' + id, function(response, status, xhr) {
      if (status == 'error') {
        var msg = 'Fehler: ';
        $('#error').html(msg + xhr.status + " " + xhr.statusText);
      }
      if (status == 'success') {
        $('#loadingTree').hide();
        $('ul#navArchive li a.link').bind('click', function(e) {
          e.preventDefault();
          updateTree($(this).attr('data-id'));
          $('td.archiv_title').html($(this).children('b:first').html());
          $('#dokument_archiv_id').val($(this).attr('data-id'));
          var ArchivTitle = $('ul#navArchive a.active b').html();
          $('#archivTitle').html(ArchivTitle);
          getVerzeichnungseinheiten($(this).attr('data-id'));
        });
      }
    });
  }

  function getVerzeichnungseinheiten(id)
  {
    $('#verzeichnungseinheit_id').load('/meine-archivalien/verzeichnungseinheiten?id=' + id, function(response, status, xhr) {
      if (status == 'error') {
        var msg = 'Fehler: ';
        $('#error').html(msg + xhr.status + " " + xhr.statusText);
      }
      if (status == 'success') {
      }
    });
  }

</script>

