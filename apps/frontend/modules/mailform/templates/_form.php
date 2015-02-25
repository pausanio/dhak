<script>
  $(function() {
    $('#mailform').submit(function(event) {
      event.preventDefault();
      $('#dia').load(
              '<?php echo url_for('mailform_send') ?>',
              {type: $('#mailform_type').val(),
                id: $('#mailform_id').val(),
                from: $('#mailform_from').val(),
                subject: $('#mailform_subject').val(),
                text: $('#mailbody').val()}
      );
    });
  });
</script>
<fieldset>
  <?php //echo $id; echo $sf_user;?>
  <form id="mailform" method="POST" action="<?php echo url_for('mailform_send') ?>">
    <table>
      <tr><td>AbsenderIn:</td><td><input id="mailform_from" type="text" name="from" value="<?php echo $from; ?>"/></td></tr>
      <tr><td>EmpfängerIn:</td><td><?php echo $rcpt; ?></td></tr>
      <tr><td>Betreff:</td><td><input id="mailform_subject" type="text" name="subject" value="<?php echo $subject; ?>"/></td></tr>
      <tr><td colspan="2">Ihre Nachricht:<br /><textarea rows="6" cols="32" id="mailbody" name="mailbody"></textarea></td></tr>
    </table>
    <input id="mailform_type" name="type" value="<?php echo $type ?>" type="hidden">
    <input id="mailform_id" name="id" value="<?php echo $id ?>" type="hidden">
    <input value="Text löschen" type="reset">&nbsp;<input name="mail_send" value="send" type="submit">
  </form>
</fieldset>