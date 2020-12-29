<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $educationID = $this->model->getEducationToDelete();

  $educationObject = $this->model->getEducationByEducationID($educationID);

  $educationOwnerID = $educationObject->employeeNumber;

  if ($educationOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to access this education record.';
    echo '</div>';
  }
  else {
    if (isset($_POST["deleteEducationSubmit"])) {
      $radioValue = $_POST["deleteeducationradio"];
      if($radioValue == "yes") {
        if(is_numeric($educationID)) {
          $this->model->deleteEducationByEducationID($educationID);
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
                <h1>Delete Education Record</h1>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <table class="table table-striped">
                  <tr>
                    <th>Subject</th>
                    <th>Level</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                  </tr>
                  <tr>
                    <?php
                    echo '<td>' . $educationObject->subject . '</td>';
                    echo '<td>' . $educationObject->level . '</td>';
                    echo '<td>' . $educationObject->fromDate . '</td>';
                    echo '<td>' . $educationObject->toDate . '</td>';
                    ?>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <h3>Are you sure you want to delete this education record? You cannot restore this education record once it has been deleted.</h3>
              </div>
            </div>

            <form action="" method="post">
              <div class="row">
                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteeducationradio" id="deleteeducationyes" value="yes" checked>
                  <label class="form-check-label" for="deleteeducationyes">
                    Yes
                  </label>
                </div>

                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteeducationradio" id="deleteeducationno" value="no">
                  <label class="form-check-label" for="deleteeducationno">
                    No
                  </label>
                </div>
              </div>

              <input type="submit" class="form-control" name="deleteEducationSubmit" value="Submit">

            </form>
          </div>
        </main>

  <?php
  }
}

?>
