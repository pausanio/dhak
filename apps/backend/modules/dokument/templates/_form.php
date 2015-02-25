<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="form-horizontal" action="<?php echo url_for('dokument/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
  <?php else: ?>
    <p>
      Laden Sie eigene Archivalien hoch und ordnen diese in der Tektonik ein.
    </p>
    <hr>
  <?php endif; ?>

  <?php if ($form->hasErrors()): ?>
    <div class="formee-msg-error">
      <p>Bitte überprüfen Sie Ihre Eingaben!</p>
    </div>
  <?php endif; ?>

  <?php echo $form ?>

  <table>
    <tbody>
      <tr>
        <th class="archiv_title">
          <label>Archivsystematik</label>
        </th>
        <td class="archiv_title">
            <?php echo $archiv_title ?>
        </td>
      </tr>
      <tr>
        <th class="verzeichnungseinheit_title">
          <label>Verzeichnungseinheit</label>
        </th>
        <td class="verzeichnungseinheit_title">
          <select id="verzeichnungseinheit_id">
            <?php if ($verzeichnungseinheiten): ?>
              <?php foreach ($verzeichnungseinheiten as $verzeichnungseinheit): ?>
                <option <?php if ($selected_ve == $verzeichnungseinheit): ?>selected="selected" <?php endif ?>value="<?php echo $verzeichnungseinheit->getId() ?>"><?php echo $verzeichnungseinheit->getTitel() ?></option>
              <?php endforeach ?>
            <?php endif ?>
          </select>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2" class="a-right">
          <a class="btn" href="<?php echo url_for('dokument/index') ?>">Zurück</a>
          <input class="btn btn-primary" type="submit" value="Speichern" />
          <input class="btn btn-primary" type="submit" name="_save_and_add" value="Speichern und Neu" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>

<script>
    $(document).ready(function() {
        $('#loadingTree').show();
        $('#archiveTree').load('<?php echo url_for('dokument/tree') ?>?id=<?php echo $archiv_id ?>', function(response, status, xhr) {
            if (status == 'error') {
                var msg = 'Fehler: : ';
                $('#loadingTree').html(msg + xhr.status + " " + xhr.statusText);
            }
            if (status == 'success') {
                $('#loadingTree').hide();
                var ArchivTitle = $('#archiveTree a.active b').html();
                $('td.archiv_title').html(ArchivTitle);
                $('ul#navArchive li a.link').bind('click', function(e) {
                    e.preventDefault();
                    var a_id = $(this).attr('data-id');
                    $('td.archiv_title').html($(this).children('b:first').html());
                    $('#dokument_archiv_id').val(a_id);
                    updateTree(a_id);
                    getVerzeichnungseinheiten(a_id);
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
            $('#archiveTree').load('<?php echo url_for('dokument/tree') ?>/tree?id=' + id, function(response, status, xhr) {
                if (status == 'error') {
                    var msg = 'Fehler: ';
                    $('#error').html(msg + xhr.status + " " + xhr.statusText);
                }
                if (status == 'success') {
                    $('#loadingTree').hide();
                    $('ul#navArchive li a.link').bind('click', function(e) {
                        e.preventDefault();
                        var a_id = $(this).attr('data-id');
                        $('#dokument_archiv_id').val(a_id);
                        $('td.archiv_title').html($(this).children('b:first').html());
                        updateTree(a_id);
                        getVerzeichnungseinheiten($(this).attr('data-id'));
                    });
                }
            });
        }

        function getVerzeichnungseinheiten(id)
        {
            $('#verzeichnungseinheit_id').load('<?php echo url_for('dokument/verzeichnungseinheiten') ?>?id=' + id, function(response, status, xhr) {
                if (status == 'error') {
                    var msg = 'Fehler: ';
                    $('#error').html(msg + xhr.status + " " + xhr.statusText);
                }
                if (status == 'success') {
                }
            });
        }
    });
</script>
