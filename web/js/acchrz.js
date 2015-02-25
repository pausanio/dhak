$(document).ready(function()
{

  lastBlock = $("#hrzacc_li01 a");
  maxWidth = 800;
  minWidth = 40;
  $("#hrzacc li a.acc").click(
          function(event) {
            event.preventDefault();
            //alert($(this).parent().width());
            if ($(this).parent().width() == maxWidth)
              return;
            var $img = $(this).children('img')[0];
            $img.src = $img.src.replace('_off', '_on');
            //$img.style.width = '50px';
            $(lastBlock).parent().children('div').hide();
            //$(lastBlock).parent().children('div').css('display', 'none');
            //$div.hide();
            //alert($div);
            $img = $(lastBlock).children('img')[0];
            $img.src = $img.src.replace('_on', '_off');
            //$img.style.width = '40px';
            $(lastBlock).parent().animate({width: minWidth + "px"}, {queue: true, duration: 200});
            $(this).parent().animate({width: maxWidth + "px"}, {queue: true, duration: 200});
            $(this).parent().children('div').show({queue: true});
            //$(this).parent().children('div').css('display', 'block');
            lastBlock = this;
          }
  );

});
