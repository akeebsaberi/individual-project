<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $projectID = $this->model->getProjectToDelete();

  $projectObject = $this->model->getProjectByProjectID($projectID);

  $projectOwnerID = $projectObject->employeeNumber;

  if ($projectOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to access this project.';
    echo '</div>';
  }
  else {
    if (isset($_POST["deleteProjectSubmit"])) {
      $radioValue = $_POST["deleteprojectradio"];
      if($radioValue == "yes") {
        if(is_numeric($projectID)) {
          $this->model->deleteProjectByProjectID($projectID);
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
                <h1>Delete Project Record</h1>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <table class="table table-striped">
                  <tr>
                    <th>Project Name</th>
                    <th>Customer</th>
                    <th>Project Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                  </tr>
                  <tr>
                    <?php
                    echo '<td>' . $projectObject->projectName . '</td>';
                    echo '<td>' . $projectObject->customer . '</td>';
                    echo '<td style="white-space:pre-wrap; word-wrap:break-word">' . $projectObject->projectDescription . '</td>';
                    echo '<td>' . $projectObject->fromDate . '</td>';
                    echo '<td>' . $projectObject->toDate . '</td>';
                    ?>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <h3>Are you sure you want to delete this project? You cannot restore this project once it has been deleted.</h3>
              </div>
            </div>

            <form action="" method="post">
              <div class="row">
                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteprojectradio" id="deleteprojectyes" value="yes" checked>
                  <label class="form-check-label" for="deleteprojectyes">
                    Yes
                  </label>
                </div>

                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteprojectradio" id="deleteprojectno" value="no">
                  <label class="form-check-label" for="deleteprojectno">
                    No
                  </label>
                </div>
              </div>

              <input type="submit" class="form-control" name="deleteProjectSubmit" value="Submit">

            </form>
          </div>
        </main>

  <?php
  }
}

?>
