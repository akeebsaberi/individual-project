<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

if (isset($_POST['search'])) {

  $response = "No data found!";

  $servername = "localhost";
  $database = "individual_project_db";
  $username = "root";
  $password = "";

  $tempConnection = mysqli_connect($servername, $username, $password, $database);

  $q = htmlspecialchars($_POST['q']);
  $q = mysqli_real_escape_string($tempConnection, $q);

  $SQLquery = "SELECT * FROM users WHERE FirstName LIKE '%$q%' OR Surname LIKE '%$q%'";

  $rows = $tempConnection->query($SQLquery);
  if ($rows && $rows->num_rows > 0) {

    $response = '<table class="table table-striped">';

    foreach($rows as $row) {
      $response .= '<tr>';
      $response .= '<td>' . $row["EmployeeNumber"] . '</td>';
      $response .= '<td><a href="?page=viewuserbyusername?' . $row["EmployeeNumber"] . '">' . $row["Username"] . '</a></td>';
      $response .= '<td>' . $row["FirstName"] . ' ' . $row["Surname"] . '</td>';
      $response .= '</tr>';
    }
    $response .= "</table>";
  }

  exit($response);
}

?>
