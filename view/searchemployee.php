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
      <div class="col-md-4" style="text-align:center;">
        <a href="?page=searchbyname" class="btn btn-primary active" role="button" aria-pressed="true">Search By Name</a>
      </div>
      <div class="col-md-4" style="text-align:center;">
        <a href="?page=searchbyskill" class="btn btn-primary active" role="button" aria-pressed="true">Search By Skill</a>
      </div>
      <div class="col-md-4" style="text-align:center;">
        <a href="?page=searchbyproject" class="btn btn-primary active" role="button" aria-pressed="true">Search By Project</a>
      </div>
    </div>
  </div>
</main
  <?php
  }
}
else {
?>
  <div class="container">
    <h1>Search For Employee</h1>
    <p>You are currently not logged in. Please login to search for employees.</p>
  </div>

<?php

}
?>
