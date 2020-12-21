<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}


if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (isset($_POST["addEmployment"])) {
    if((!isset($_POST['employmentID'])) || (!isset($_POST['employeeNumber'])) || (!isset($_POST['company'])) || (!isset($_POST['fromDate'])) || (!isset($_POST['toDate']))) {
      echo "<br />Please fill out all required fields";
    }
    else {
      $result = $this->model->addNewEmploymentToDatabase($_POST["employmentID"], $_POST["employeeNumber"], $_POST["company"], $_POST["fromDate"], $_POST["toDate"]);
    }
  }

  $maximumEmploymentID = $this->model->getEmploymentWithHighestID();
  $newEmploymentID = $maximumEmploymentID + 1;

?>


  <main>
    <div class="container">
      <h1>Add New Employment Record</h1>
      <form action="" method="post">

        <?php
          echo '<input type="hidden" class="form-control" name="employmentID" value="' . $newEmploymentID . '">';
          echo '<input type="hidden" class="form-control" name="employeeNumber" value="' . $_SESSION['user']['EmployeeNumber'] . '">';
        ?>

        <h4>Company</h4>
        <input type="text" class="form-control" name="company">

        <h4>Start Date</h4>
        <input type="text" class="form-control" name="fromDate">

        <h4>End Date (please leave blank if you are still employed by this company)</h4>
        <input type="text" class="form-control" name="toDate">

        <input type="submit" class="form-control" name="addEmployment" value="Add Employment">

      </form>
    </div>
  </main>


<?php

}

?>
