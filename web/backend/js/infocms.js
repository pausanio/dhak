function checkAll()
{
  var boxes = document.getElementsByTagName('input'); for(index in boxes) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
}
$(document).ready(function()  {
    
  $("#main_list").treetable({
    treeColumn: 2,
    initialState: 'expanded'
  });
  // Configure draggable nodes
  $("#main_list .file, #main_list .folder").draggable({
    helper: "clone",
    opacity: .75,
    refreshPositions: true, // Performance?
    revert: "invalid",
    revertDuration: 300,
    scroll: true
  });
  

  $("#main_list .file, #main_list .folder").each(function() {
    $(this).parents("tr").droppable({
      accept: ".file, .folder",
      drop: function(e, ui) {
        var droppedEl = ui.draggable.parents("tr");
        $("#main_list").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
        var parentId = droppedEl.attr("id");
        var thisId = this.id;
        $("#select_" + parentId).val(thisId.substr(5));
      },
      hoverClass: "accept",
      over: function(e, ui) {
        var droppedEl = ui.draggable.parents("tr");
        if(this != droppedEl[0] && !$(this).is(".expanded")) {
          $("#main_list").treetable("expandNode", $(this).data("ttId"));
        }
      }
    });
  });
  

  // Make visible that a row is clicked
  $("table#main_list tbody tr").mousedown(function() {
    $("tr.selected").removeClass("selected"); // Deselect currently selected rows
    $(this).addClass("selected");
  });

  // Make sure row is selected when span is clicked
  $("table#main_list tbody tr span").mousedown(function() {
    $($(this).parents("tr")[0]).trigger("mousedown");
  });
  
  //update route field
  $("#cms_info_title").focusout(function() {
      var parentId = $("select#cms_info_parent_id").val(),
          title = $("#cms_info_title").val();
      $.post('/backend_dev.php/infocms/updateroute', {parentId: parentId, title: title}, function(data) {
        $("#cms_info_route_name").val(data);
        });
  });
});
