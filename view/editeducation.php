<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  if(isset($_POST["editEducationDetails"])) {
    if(!isset($_POST['subject']) || !isset($_POST['level']) || !isset($_POST['fromDate']) || !isset($_POST['toDate'])) {
      echo "<br />Please fill out all required fields";
    }
    else {
      $result =  $this->model->editEducationDetails($_POST['educationID'], $_POST['subject'], $_POST['level'], $_POST['fromDate'], $_POST['toDate']);
    }
  }
  $ID = $this->model->getEducationToEdit();

  if(is_int($ID)) {
    $educationObject = $this->model->getEducationToBeEdited($ID);
  }

?>

<main>

  <div class="container">
    <h1>Edit Education</h1>
    <form action="" method="post">
      <?php
        echo '<input type="hidden" class="form-control" name="educationID" value="' . $educationObject->educationID . '">';
        echo '<h4>Subject</h4>';
        echo '<input type="text" class="form-control" name="subject" value="' . $educationObject->subject . '">';
        echo '<h4>Level</h4>';
        echo '<input type="text" class="form-control" name="level" value="' . $educationObject->level . '">';
        echo '<h4>Start Date</h4>';
        echo '<input type="text" class="form-control" name="fromDate" value="' . $educationObject->fromDate . '">';
        echo '<h4>End Date (please leave blank if you are still on this course)</h4>';
        echo '<input type="text" class="form-control" name="toDate" value="' . $educationObject->toDate . '">';

        echo '<input type="submit" class="form-control" name="editEducationDetails" value="Update Education">';
      ?>
    </form>
  </div>

</main>

<?php

}

?>
