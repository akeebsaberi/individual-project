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

  $employmentID = $this->model->getEmploymentToDelete();

  $employmentObject = $this->model->getEmploymentByEmploymentID($employmentID);

  $employmentOwnerID = $employmentObject->employeeNumber;

  if ($employmentOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to access this employment record.';
    echo '</div>';
  }
  else {
    if (isset($_POST["deleteEmploymentSubmit"])) {
      $radioValue = $_POST["deleteemploymentradio"];
      if($radioValue == "yes") {
        if(is_numeric($employmentID)) {
          $this->model->deleteEmploymentByEmploymentID($employmentID);
        }
      }
      else if($radioValue == "no") {
        require("view/managecv.php");
        exit;
      }
    }

  ?>

        <main>
          <div class="container">

            <div class="row">
              <div class="col-xs-12">
                <h1>Delete Employment Record</h1>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <table class="table table-striped">
                  <tr>
                    <th>Company</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                  </tr>
                  <tr>
                    <?php
                    echo '<td>' . $employmentObject->company . '</td>';
                    echo '<td>' . $employmentObject->fromDate . '</td>';
                    echo '<td>' . $employmentObject->toDate . '</td>';
                    ?>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <h3>Are you sure you want to delete this employment record? You cannot restore this employment record once it has been deleted.</h3>
              </div>
            </div>

            <form action="" method="post">
              <div class="row">
                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteemploymentradio" id="deleteemploymentyes" value="yes" checked>
                  <label class="form-check-label" for="deleteemploymentyes">
                    Yes
                  </label>
                </div>

                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteemploymentradio" id="deleteemploymentno" value="no">
                  <label class="form-check-label" for="deleteemploymentno">
                    No
                  </label>
                </div>
              </div>

              <input type="submit" class="form-control" name="deleteEmploymentSubmit" value="Submit">

            </form>
          </div>
        </main>

  <?php
  }
}

?>
