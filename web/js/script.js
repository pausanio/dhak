/**
 * Custom jquery scripts.
 *
 * @author  Maik Mettenheimer <mettenheimer@pausanio.de>
 * @since   2011-12-06
 */

$(function() {
  //sfWebDebugToggleMenu();
});

function clearValue(e, def)
{
  if (e.value == def)
    e.value = '';
  return false;
}

$(document).ready(function()
{

  /**
   * Initialise superfish plugin (Main Navigation)
   *
   * @see http://users.tpg.com.au/j_birch/plugins/superfish/
   */
  $('ul.sf-menu').superfish({
    pathLevels: 1,
    delay: 800,
    animation: {
      opacity: 'show'
    },
    speed: 'normal',
    autoArrows: false,
    dropShadows: false,
    disableHI: false
  });

  /**
   * Login Menue
   */
  $("#loginlink").click(function(e) {
    e.preventDefault();
    $(".loginWrapper").toggle();
    $("#loginlink").toggleClass("menu-open");
  });

  $(".loginWrapper").mouseup(function() {
    return false;
  });
  $(document).mouseup(function(e) {
    if ($(e.target).parent("a#loginlink").length == 0) {
      $("#loginlink").removeClass("menu-open");
      $(".loginWrapper").hide();
    }
  });

  /**
   * Initialise jQuery listnav plugin (Supporter)
   *
   * @see http://www.ihwy.com/Labs/jquery-listnav-plugin.aspx
   */
  $('#supporterList').listnav({
    includeAll: true,
    showCounts: true,
    noMatchText: 'Keine Einträge gefunden'
  });

  /**
   * News accordion toggle (News)
   */
  $("div.article h3").click(
          function(event) {
            event.preventDefault();
            $(this).next("div.news-text").toggle();
          }
  );

  /**
   * FAQ accordion toggle (FAQ)
   */
  $("div.question > h3").click(
          function(event) {
            event.preventDefault();
            if ($(this).parent().hasClass("open")) {
              $(this).parent().removeClass("open");
            } else {
              $("div.question").removeClass("open");
              $(this).parent().addClass("open");
            }
          }
  );

  /**
   * FAQ mail form validation (FAQ)
   */
  $("#mail_send").click(
          function(event) {
            event.preventDefault();
            $(".mail_email_error").hide();
            $(".mail_name_error").hide();
            $(".mail_comment_error").hide();
            $.get($("#mailform").attr("action"), {
              type: $("#mail_type").val(),
              from: $("#mail_email").val(),
              subject: $("#mail_name").val(),
              text: $("#mail_comment").val()
            },
            function(data) {
              if (!data.success) {
                if (data.errors.mail_email) {
                  $(".mail_email_error").show();
                }
                if (data.errors.mail_name) {
                  $(".mail_name_errorr").show();
                }
                if (data.errors.mail_comment) {
                  $(".mail_comment_error").show();
                }
                alert(data.success);
              } else {
                $("#mailform").html("<h2>Ihre Frage wurde an uns versendet.</h2>");
              }
            }, "json");
          }
  );

  /**
   * jQuery Expander Plugin
   *
   * The Expander Plugin hides (collapses) a portion of an element's content
   * and adds a "read more" link so that the text can be viewed by the user
   * if he or she wishes.
   *
   * @source https://github.com/kswedberg/jquery-expander
   */
  $('div.expander').expander({
    slicePoint:       300,
    expandPrefix:     ' ',
    expandText:       '[mehr]',
    userCollapseText: '[weniger]',
    onCollapse: function (){
            setTimeout(1200, $("html, body").animate({ scrollTop: 0 }, 600));
    }
  });

});

