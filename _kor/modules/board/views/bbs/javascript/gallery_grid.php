<script>
  var options = {
    autoResize: true, // This will auto-update the layout when the browser window is resized.
    container: $('#gridgallery'), // Optional, used for some extra CSS styling
    offset: 2, // Optional, the distance between grid items
    itemWidth: 210, // Optional, the width of a grid item
    fillEmptySpace: true // Optional, fill the bottom of each column with widths of flexible height
  };

  $('#tiles li').wookmark(options);
</script>