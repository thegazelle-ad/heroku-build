$(function(){ //DOM Ready
    $(".gridster ul").gridster({
        widget_margins: [10, 10],
        widget_base_dimensions: [200, 200],
        max_cols: 3,
        serialize_params: function(jqueryObj, wgd) {
          return {
            id: jqueryObj.attr("data-post_id"),
            row: wgd.row,
            index: wgd.col,
            span: wgd.size_x
          };
        }
    });
    
    var gridster = $(".gridster ul").gridster().data('gridster');

    $(document).on("click", "#ungridded a", function(e) {
      e.preventDefault();
      var target = $(e.target);
      var id = target.data("post_id");
      var title = target.html().slice(1);
      gridster.add_widget('<li data-post_id=' + id + '" >' +
              '<div class="gridster-box">' +
                '<div class="row gridster-title">' +
                  title +
                '</div>' +
                '<div class="row">' +
                  '<button type="button" class="btn btn-info btn-block toggle-btn">Toggle Size</button>' +
                '</div>' +
                '<div class="row">' +
                  '<button type="button" class="btn btn-danger btn-block remove-btn">Remove</button>' +
                '</div>' +
              '</div>' + 
            '</li>', 1, 1);
      target.parent().remove();
    });

    $(document).on("click", ".toggle-btn", function(e) {
      var box = $(e.target).parent().parent().parent();
      var size = parseInt(box.attr("data-sizex"), 10);
      console.log(size);
      if (size != 3) {
        gridster.resize_widget(box, size + 1, 1);
      } else {
        gridster.resize_widget(box, 1, 1);
      }
    });

    $(document).on("click", ".remove-btn", function(e) {
      var box = $(e.target).parent().parent().parent();
      var title = $(".gridster-title", box).html().replace(/^\s+|\s+$/g, "");
      var id = box.attr("data-post_id");
      $("#ungridded").append("<li><a href='#' data-post_id='" + id + "' class='text-primary'>+" + title + 
        "</a></li>");
      gridster.remove_widget(box);
    });

    $(document).on("click", "#btn-save", function(e) {
      var data = gridster.serialize();
      var root_url = $(".gridster").attr("data-root");
      $.post(root_url + "/?gridster", {gridlock: data});
    });

    $(document).on("click", "#btn-preview", function(e) {
    });

});
