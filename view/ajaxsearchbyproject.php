<?php

if (isset($_POST['search'])) {

  $response = "No data found!";

  $servername = "localhost";
  $database = "individual_project_db";
  $username = "root";
  $password = "";

  $tempConnection = mysqli_connect($servername, $username, $password, $database);

  $q = htmlspecialchars($_POST['q']);
  $q = mysqli_real_escape_string($tempConnection, $q);

  $SQLquery = "SELECT u.*, p.* FROM users u INNER JOIN projects p ON u.EmployeeNumber = p.EmployeeNumber WHERE p.ProjectName LIKE '%$q%' OR p.Customer LIKE '%$q%';";

  $rows = $tempConnection->query($SQLquery);
  if ($rows && $rows->num_rows > 0) {

    $arrayOfIDs = array();

    $response = '<table class="table table-striped">';

    foreach($rows as $row) {

      $IDFromDatabase = $row["EmployeeNumber"];

      if (!in_array($IDFromDatabase, $arrayOfIDs)) {
        $response .= '<tr>';
        $response .= '<td>' . $row["EmployeeNumber"] . '</td>';
        $response .= '<td><a href="?page=viewuserbyusername?' . $row["EmployeeNumber"] . '">' . $row["Username"] . '</a></td>';
        $response .= '<td>' . $row["FirstName"] . ' ' . $row["Surname"] . '</td>';
        $response .= '<td>' . $row["ProjectName"] . '</td>';
        $response .= '<td>' . $row["Customer"] . '</td>';
        $response .= '</tr>';

        array_push($arrayOfIDs, $IDFromDatabase);
      }


    }
    $response .= "</table>";
  }

  exit($response);
}

?>
