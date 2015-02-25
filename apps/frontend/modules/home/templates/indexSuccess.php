<?php slot('title', sprintf('%s Home', get_slot('title'))); ?>

<script type="text/javascript">
  $(document).ready(function(){
    $("a.banner").click(
    function(event){
      event.preventDefault();
      $("div.info").removeClass("active");
      $(this).parent().addClass("active");
    }
  );
  });
</script>
<?php include_component('home', 'slider') ?>
