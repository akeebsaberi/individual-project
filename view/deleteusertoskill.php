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

  $userToSkillID = $this->model->getUserToSkillToDelete();

  $userToSkillObject = $this->model->getUserToSkillByUserToSkillID($userToSkillID);

  $userToSkillOwnerID = $userToSkillObject->employeeNumber;

  if ($userToSkillOwnerID != $_SESSION['user']['EmployeeNumber']) {
    echo '<div class="container">';
    echo 'You do not have permission to access this skill.';
    echo '</div>';
  }
  else {
    if (isset($_POST["deleteUserToSkillSubmit"])) {
      $radioValue = $_POST["deleteusertoskillradio"];
      if($radioValue == "yes") {
        if(is_numeric($userToSkillID)) {
          $this->model->deleteUserToSkillByUserToSkillID($userToSkillID);
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
                <h1>Delete Skill Record</h1>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <table class="table table-striped">
                  <tr>
                    <th>Skill</th>
                    <th>Core Skill</th>
                    <th>Competency Level</th>
                    <th>Experience in Years</th>
                  </tr>
                  <tr>
                    <?php
                    echo '<td>' . $userToSkillObject->skillName . '</td>';
                    echo '<td>' . $userToSkillObject->isCoreSkill . '</td>';
                    echo '<td>' . $userToSkillObject->competencyLevel . '</td>';
                    echo '<td>' . $userToSkillObject->experienceInYears . '</td>';
                    ?>
                  </tr>
                </table>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12">
                <h3>Are you sure you want to delete this skill record? You cannot restore this skill record once it has been deleted.</h3>
              </div>
            </div>

            <form action="" method="post">
              <div class="row">
                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteusertoskillradio" id="deleteusertoskillyes" value="yes" checked>
                  <label class="form-check-label" for="deleteusertoskillyes">
                    Yes
                  </label>
                </div>

                <div class="form-check col-xs-12">
                  <input class="form-check-input" type="radio" name="deleteusertoskillradio" id="deleteusertoskillno" value="no">
                  <label class="form-check-label" for="deleteusertoskillno">
                    No
                  </label>
                </div>
              </div>

              <input type="submit" class="form-control" name="deleteUserToSkillSubmit" value="Submit">

            </form>
          </div>
        </main>

  <?php
  }
}

?>
