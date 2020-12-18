<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}


if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (isset($_POST["addNewEducation"])) {
    if((!isset($_POST['educationID'])) || (!isset($_POST['employeeNumber'])) || (!isset($_POST['subject'])) || (!isset($_POST['level'])) || (!isset($_POST['fromDate'])) || (!isset($_POST['toDate']))) {
      echo "<br />Please fill out all required fields";
    }
    else {
      $result = $this->model->addNewEducationToDatabase($_POST["educationID"], $_POST["employeeNumber"], $_POST["subject"], $_POST["level"], $_POST["fromDate"], $_POST["toDate"]);
    }
  }

  $maximumEducationID = $this->model->getEducationWithHighestID();
  $newEducationID = $maximumEducationID + 1;

?>


  <main>
    <div class="container">
      <h1>Add New Education</h1>
      <form action="" method="post">

        <?php
          echo '<input type="hidden" class="form-control" name="educationID" value="' . $newEducationID . '">';
          echo '<input type="hidden" class="form-control" name="employeeNumber" value="' . $_SESSION['user']['EmployeeNumber'] . '">';
        ?>

        <h4>Subject</h4>
        <input type="text" class="form-control" name="subject">

        <h4>Level</h4>
        <input type="text" class="form-control" name="level">

        <h4>Start Date</h4>
        <input type="text" class="form-control" name="fromDate">

        <h4>End Date (please leave blank if you are still on this course)</h4>
        <input type="text" class="form-control" name="toDate">

        <input type="submit" class="form-control" name="addNewEducation" value="Add Education">

      </form>
    </div>
  </main>


<?php

}

?>
