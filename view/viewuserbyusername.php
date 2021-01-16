<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  $employeeIDToSearch = $this->model->getEmployeeIDToView();
  if (!isset($employeeIDToSearch)) {
    echo '<div class="container">';
    echo '<p>This operation has failed. Please try again.</p>';
    echo '</div>';
  }
  else {
    $userSearch = $this->model->getUserDetailsByEmployeeNumber($employeeIDToSearch);

    if($userSearch != null) {
      $employeeEmployeeNumber = $userSearch->employeeNumber;
      $employeeFirstName = $userSearch->firstName;
      $employeeSurname = $userSearch->surname;
      $employeeUsername = $userSearch->username;
      $employeePassword = $userSearch->password;
      $employeeIsResourceManager = $userSearch->isResourceManager;
      $employeeIsAdmin = $userSearch->isAdmin;
      $employeeDateOfBirth = $userSearch->dateOfBirth;
      $employeeBaseLocation = $userSearch->baseLocation;
      $employeeLineManager = $userSearch->lineManager;
      $employeeReviewerManager = $userSearch->reviewerManager;
      $employeeResourceManager = $userSearch->resourceManager;
      $employeeBusinessUnit = $userSearch->businessUnit;
      $employeeGrade = $userSearch->grade;

      $employeeSurnameLastChar = substr($employeeSurname, -1);
      if ($employeeSurnameLastChar == "s") {
        $apostrophe = "'";
      }
      else {
        $apostrophe = "'s";
      }

      $gradeObject = $this->model->constructGradeFromGradeID($employeeGrade);
      $gradeJobTitle = $gradeObject->jobTitle;
      $gradeObjectCode = $gradeObject->gradeCode;

      $lineManager = $this->model->getUserDetailsByEmployeeNumber($employeeLineManager);
      $lineManagerName = "" . $lineManager->firstName . " " . $lineManager->surname;

      $resourceManager = $this->model->getUserDetailsByEmployeeNumber($employeeResourceManager);
      $resourceManagerName = "" . $resourceManager->firstName . " " . $resourceManager->surname;

      $reviewerManager = $this->model->getUserDetailsByEmployeeNumber($employeeReviewerManager);
      $reviewerManagerName = "" . $reviewerManager->firstName . " " . $reviewerManager->surname;

      $baseLocation = $this->model->constructBaseLocationFromBaseLocationID($employeeBaseLocation);
      $baseLocationName = $baseLocation->baseLocationName;
      $baseLocationCity = $baseLocation->city;
      $baseLocationCountry = $baseLocation->country;

      $businessUnit = $this->model->constructBusinessUnitFromBusinessUnitID($employeeBusinessUnit);
      $businessUnitName = $businessUnit->unit;

      $projectResult = $this->model->getAllProjectsAssociatedWithThisUser($employeeEmployeeNumber);
      $educationResult = $this->model->getAllEducationAssociatedWithThisUser($employeeEmployeeNumber);
      $userToSkillsResult = $this->model->getAllSkillsAssociatedWithThisUser($employeeEmployeeNumber);
      $employmentResult = $this->model->getAllEmploymentAssociatedWithThisUser($employeeEmployeeNumber);

      ?>

      <main>
        <section id="user-profile-name" class="jumbotron">
          <div class="container">
            <?php
              echo '<h2>' . $employeeFirstName . ' ' . $employeeSurname . $apostrophe . ' CV</h2>';
              echo '<h3>' . $gradeJobTitle . '</h3>';
            ?>
          </div>
        </section>
      </main>

      <div class="container">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1">
                  <h3>Profile</h3>
                </a>
              </h4>
            </div>

            <!-- Consultant Information -->
            <div id="collapse1" class="panel-collapse collapse">
              <div class="panel-body">
                <div>
                  <h4>Consultant Information</h4>
                  <table class="table table-striped">

                    <?php

                    echo '<tr>';
                      echo '<th>Employee Number</th>';
                      echo '<td>' . $employeeEmployeeNumber . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Username</th>';
                      echo '<td>' . $employeeUsername . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>First Name</th>';
                      echo '<td>' . $employeeFirstName . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Surname</th>';
                      echo '<td>' . $employeeSurname . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Date of Birth</th>';
                      echo '<td>' . $employeeDateOfBirth . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Line Manager</th>';
                      echo '<td>' . $lineManagerName . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th></th>';
                      echo '<td></td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Grade</th>';
                      echo '<td>' . $gradeObjectCode . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Base Location</th>';
                      echo '<td>' . $baseLocationName . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>City</th>';
                      echo '<td>' . $baseLocationCity . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Country</th>';
                      echo '<td>' . $baseLocationCountry . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Resource Manager</th>';
                      echo '<td>' . $resourceManagerName . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Reviewer Manager</th>';
                      echo '<td>' . $reviewerManagerName . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                      echo '<th>Business Unit</th>';
                      echo '<td>' . $businessUnitName . '</td>';
                    echo '</tr>';
                    ?>

                  </table>
                </div>

              </div>
              <div class="panel-footer"><?php echo "$employeeFirstName $employeeSurname$apostrophe";  ?> Profile</div>
            </div>

          </div>
        </div>
      </div>




      <div class="container">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse2">
                  <h3>Projects</h3>
                </a>
              </h4>
            </div>

            <!-- Consultant Information -->
            <div id="collapse2" class="panel-collapse collapse">
              <div class="panel-body">
                <div>
                  <h4>Projects</h4>


                  <?php
                    if ($projectResult == 0) {
                      echo "$employeeFirstName $employeeSurname currently has no projects to display.";
                    }
                    else {

                  ?>
                      <table class="table table-striped">

                        <tr>
                          <th>Project Name</th>
                          <th>Customer</th>
                          <th>Project Description</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                        </tr>
                  <?php
                      foreach($projectResult as $row) {
                        echo '<tr>';
                        echo '<td>' . $row->projectName . '</td>';
                        echo '<td>' . $row->customer . '</td>';
                        echo '<td>' . $row->projectDescription . '</td>';
                        echo '<td>' . $row->fromDate . '</td>';
                        echo '<td>' . $row->toDate . '</td>';
                        echo '</tr>';
                      }
                    }
                  ?>

                  </table>
                </div>

              </div>
              <div class="panel-footer"><?php echo "$employeeFirstName $employeeSurname$apostrophe";  ?> Projects</div>
            </div>

          </div>
        </div>
      </div>




      <div class="container">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse3">
                  <h3>Education</h3>
                </a>
              </h4>
            </div>

            <!-- Consultant Information -->
            <div id="collapse3" class="panel-collapse collapse">
              <div class="panel-body">
                <div>
                  <h4>Education</h4>


                  <?php
                    if ($educationResult == 0) {
                      echo "$employeeFirstName $employeeSurname currently has not added any education records.";
                    }
                    else {
                  ?>
                      <table class="table table-striped">

                        <tr>
                          <th>Subject</th>
                          <th>Level</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                        </tr>
                    <?php
                      foreach($educationResult as $row) {
                        echo '<tr>';
                        echo '<td>' . $row->subject . '</td>';
                        echo '<td>' . $row->level . '</td>';
                        echo '<td>' . $row->fromDate . '</td>';
                        echo '<td>' . $row->toDate . '</td>';
                        echo '</tr>';
                      }
                    }
                  ?>

                  </table>
                </div>

              </div>
              <div class="panel-footer"><?php echo "$employeeFirstName $employeeSurname$apostrophe";  ?> Education</div>
            </div>

          </div>
        </div>
      </div>





      <div class="container">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse4">
                  <h3>Skills</h3>
                </a>
              </h4>
            </div>

            <!-- Consultant Information -->
            <div id="collapse4" class="panel-collapse collapse">
              <div class="panel-body">
                <div>
                  <h4>Skills</h4>


                  <?php
                    if ($userToSkillsResult == 0) {
                      echo "$employeeFirstName $employeeSurname has not added any skills.";
                    }
                    else {
                  ?>
                      <table class="table table-striped">

                        <tr>
                          <th>Skill</th>
                          <th>Skill Type</th>
                          <th>Core Skill</th>
                          <th>Competency Level</th>
                          <th>Experience In Years</th>
                        </tr>
                  <?php
                      foreach($userToSkillsResult as $row) {
                        echo '<tr>';
                        echo '<td>' . $row->skillName . '</td>';
                        echo '<td>' . $row->skillType . '</td>';
                        echo '<td>' . $row->isCoreSkill . '</td>';
                        echo '<td>' . $row->competencyLevel . '</td>';
                        echo '<td>' . $row->experienceInYears . '</td>';
                        echo '</tr>';
                      }
                    }


                  ?>

                  </table>
                </div>

              </div>
              <div class="panel-footer"><?php echo "$employeeFirstName $employeeSurname$apostrophe";  ?> Skills</div>
            </div>

          </div>
        </div>
      </div>



      <div class="container">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse5">
                  <h3>Employment</h3>
                </a>
              </h4>
            </div>

            <div id="collapse5" class="panel-collapse collapse">
              <div class="panel-body">
                <div>
                  <h4>Employment</h4>

                  <?php
                    if ($employmentResult == 0) {
                      echo "$employeeFirstName $employeeSurname currently has no employment records to display.";
                    }
                    else {
                  ?>
                      <table class="table table-striped">

                        <tr>
                          <th>Company</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                        </tr>
                  <?php
                      foreach($employmentResult as $row) {
                        echo '<tr>';
                        echo '<td>' . $row->company . '</td>';
                        echo '<td>' . $row->fromDate . '</td>';
                        echo '<td>' . $row->toDate . '</td>';
                        echo '</tr>';
                      }
                    }

                  ?>

                  </table>
                </div>

              </div>
              <div class="panel-footer"><?php echo "$employeeFirstName $employeeSurname$apostrophe";  ?> Employment</div>
            </div>

          </div>
        </div>
      </div>

    <?php
    }
    else {
      echo '<div class="container">';
      echo '<p>No user found. Please try again.</p>';
      echo '</div>';
    }
  }
}
else {
  echo '<div class="container">';
  echo '<h1>View User Details</h1>';
  echo '<p>You are not logged in</p>';
  echo '</form>';
  echo '</div>';
}
?>
