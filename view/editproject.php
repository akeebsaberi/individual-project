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

  $projectID = $this->model->getProjectToEdit();

  $projectObject = $this->model->getProjectByProjectID($projectID);

  $projectOwnerID = $projectObject->employeeNumber;

  if ($projectOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to edit this project.';
    echo '</div>';
  }
  else {
    if (isset($_POST["editProjectDetails"])) {
      if ((!isset($_POST['projectName'])) || (!isset($_POST['customer'])) || (!isset($_POST['projectDescription'])) || (!isset($_POST['fromDate'])) || (!isset($_POST['toDate']))) {
        echo "<br />Please fill out all required fields";
      }
      else {
        $result = $this->model->editProjectDetails($_POST["projectID"], $_POST["projectName"], $_POST["customer"], $_POST["projectDescription"], $_POST["fromDate"], $_POST["toDate"]);
      }
    }
    $ID = $this->model->getProjectToEdit();

    if (is_int($ID)) {
      $projectObject = $this->model->getProjectToBeEdited($ID);
    }

    ?>

    <main>

      <div class="container">
        <h1>Edit Project</h1>
        <form action="" method="post">
          <?php
            echo '<input type="hidden" class="form-control" name="projectID" value="' . $projectObject->projectID . '">';
            echo '<h4>Project Name</h4>';
            echo '<input type="text" class="form-control" name="projectName" value="' . $projectObject->projectName . '">';
            echo '<h4>Customer</h4>';
            echo '<input type="text" class="form-control" name="customer" value="' . $projectObject->customer . '">';
            echo '<h4>Project Description</h4>';
            echo '<textarea type="text" class="form-control" name="projectDescription">' . $projectObject->projectDescription . '</textarea>';
            echo '<h4>Start Date</h4>';
            echo '<input type="text" class="form-control" name="fromDate" value="' . $projectObject->fromDate . '">';
            echo '<h4>End Date (please leave blank if you are still on this project)</h4>';
            echo '<input type="text" class="form-control" name="toDate" value="' . $projectObject->toDate . '">';

            echo '<input type="submit" class="form-control" name="editProjectDetails" value="Update Project">';
          ?>
        </form>
      </div>

    </main>

  <?php
  }
}

?>
