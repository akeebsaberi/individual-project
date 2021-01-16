<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (($_SESSION['user']['IsAdmin'] != 1) || ($_SESSION['user']['IsResourceManager'] != 1)) {
    echo '<div class="container">';
    echo '<h1>Search For Employee</h1>';
    echo '<div class="row">';
    echo '<div class="col-xs-12">';
    echo '<p>You do not have permission to search for employees.</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
  else {

?>

<main>
  <div class="container">
    <h1>Search For Employee</h1>
    <div class="row">
      <div class="col-xs-12">
        <h4>Search By Name</h4>
      </div>
    </div>
    <div class="row">
      <form action="" method="post" id="search-by-name">
        <div class="col-xs-12">
          <input type="text" class="form-control" name="searchByNameInput" value="" placeholder="Search By Name..." id="search-by-name-input" >
        </div>
        <!-- <div class="col-md-3">
          <input type="submit" class="form-control" name="searchByNameAjax" value="Search For Employee" >
        </div> -->
      </form>
    </div>
    <div class="row">
      <div id="response" class="col-xs-12">
      </div>
    </div>
  </div>
</main>

<script type="text/javascript">
  $(document).ready(function(){
    $("#search-by-name-input").keyup(function(){
      var query = $("#search-by-name-input").val();

      if (query.length > 0) {
        $.ajax({
          url: '/individual-project/view/ajaxsearchbyname.php',
          method:'POST',
          data: {
            search: 1,
            q: query
          },
          success: function(data) {
            $("#response").html(data);
          },
          dataType: 'text'
        });
      }
    });
  });
</script>

  <?php
  }
}
?>
