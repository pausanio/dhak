$(document).ready(function()
{

  $("a.banner").click(
          function(event) {
            event.preventDefault();
            $("div.info").removeClass("active");
            $(this).parent().addClass("active");
          }
  );

});
