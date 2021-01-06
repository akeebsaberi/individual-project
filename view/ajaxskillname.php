<?php

if (isset($_POST['search'])) {

  $response = '<div class="container">';
  $response = '<div class="row">';
  $response .= '<p class="col-xs-12">No data found</p>';
  $response .= '</div>';
  $response .= '</div>';

  $servername = "localhost";
  $database = "individual_project_db";
  $username = "root";
  $password = "";

  $tempConnection = mysqli_connect($servername, $username, $password, $database);

  $q = htmlspecialchars($_POST['q']);

  $SQLquery = "SELECT * FROM skills WHERE SkillName LIKE '%$q%'";

  $rows = $tempConnection->query($SQLquery);

  if ($rows && $rows->num_rows > 0) {
    $response = '<table class="table table-striped">';
    foreach ($rows as $row) {
      $response .= '<tr>';
      $response .= '<td>';
      $response .= '<a href="#">' . $row["SkillName"] . '</a>';

      $response .= '</td>';
      $response .= '</tr>';
    }
    $response .= "</table>";
  }

  exit($response);
}

?>
