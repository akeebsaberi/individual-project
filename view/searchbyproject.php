<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

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
        <h4>Search By Project</h4>
      </div>
    </div>
    <div class="row">
      <form action="" method="post" id="search-by-project">
        <div class="col-xs-12">
          <input type="text" class="form-control" name="searchByProjectInput" value="" placeholder="Search By Project..." id="search-by-project-input" >
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
    $("#search-by-project-input").keyup(function(){
      var query = $("#search-by-project-input").val();

      if (query.length > 0) {
        $.ajax({
          url: '/individual-project/view/ajaxsearchbyproject.php',
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
