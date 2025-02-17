<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $employmentID = $this->model->getEmploymentToEdit();

  $employmentObject = $this->model->getEmploymentByEmploymentID($employmentID);

  $employmentOwnerID = $employmentObject->employeeNumber;

  if ($employmentOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to edit this employment record.';
    echo '</div>';
  }
  else {
    if (isset($_POST["editEmploymentDetails"])) {
      if (!isset($_POST['company']) || !isset($_POST['fromDate']) || !isset($_POST['toDate'])) {
        echo "<br />Please fill out all required fields";
      }
      else {
        $result =  $this->model->editEmploymentDetails($_POST['employmentID'], $_POST['company'], $_POST['fromDate'], $_POST['toDate']);
      }
    }
    $ID = $this->model->getEmploymentToEdit();

    if (is_int($ID)) {
      $employmentObject = $this->model->getEmploymentToBeEdited($ID);
    }

  ?>

  <main>

    <div class="container">
      <h1>Edit Employment</h1>
      <form action="" method="post">
        <?php
          echo '<input type="hidden" class="form-control" name="employmentID" value="' . $employmentObject->employmentID . '">';
          echo '<h4>Company</h4>';
          echo '<input type="text" class="form-control" name="company" value="' . $employmentObject->company . '">';
          echo '<h4>Start Date</h4>';
          echo '<input type="text" class="form-control" name="fromDate" value="' . $employmentObject->fromDate . '">';
          echo '<h4>End Date (please leave blank if you are still on this course)</h4>';
          echo '<input type="text" class="form-control" name="toDate" value="' . $employmentObject->toDate . '">';

          echo '<input type="submit" class="form-control" name="editEmploymentDetails" value="Update Employment">';
        ?>
      </form>
    </div>

  </main>

  <?php
  }
}

?>
