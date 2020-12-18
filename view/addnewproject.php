<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}


if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (isset($_POST["addNewProject"])) {
    if((!isset($_POST['projectID'])) || (!isset($_POST['employeeNumber'])) || (!isset($_POST['projectName'])) || (!isset($_POST['customer'])) || (!isset($_POST['projectDescription'])) || (!isset($_POST['fromDate'])) || (!isset($_POST['toDate']))) {
      echo "<br />Please fill out all required fields";
    }
    else {
      $result = $this->model->addNewProjectToDatabase($_POST["projectID"], $_POST["employeeNumber"], $_POST["projectName"], $_POST["customer"], $_POST["projectDescription"], $_POST["fromDate"], $_POST["toDate"]);
    }
  }

  $maximumProjectID = $this->model->getProjectWithHighestID();
  $newProjectID = $maximumProjectID + 1;

?>


  <main>
    <div class="container">
      <h1>Add New Project</h1>
      <form action="" method="post">

        <?php
          echo '<input type="hidden" class="form-control" name="projectID" value="' . $newProjectID . '">';
          echo '<input type="hidden" class="form-control" name="employeeNumber" value="' . $_SESSION['user']['EmployeeNumber'] . '">';
        ?>

        <h4>Project Name</h4>
        <input type="text" class="form-control" name="projectName">

        <h4>Customer</h4>
        <input type="text" class="form-control" name="customer">

        <h4>Project Description</h4>
        <textarea type="text" class="form-control" name="projectDescription"></textarea>

        <h4>Start Date</h4>
        <input type="text" class="form-control" name="fromDate">

        <h4>End Date (please leave blank if you are still on this project)</h4>
        <input type="text" class="form-control" name="toDate">

        <input type="submit" class="form-control" name="addNewProject" value="Add Project">

      </form>
    </div>
  </main>


<?php

}

?>
