<?php

//Start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

//Check if user is authenticated (username session variable set)
if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  //Get grade object and obtain corresponding job title and grade code
  $gradeObject = $this->model->constructGradeFromGradeID($_SESSION['user']['Grade']);
  $gradeJobTitle = $gradeObject->jobTitle;
  $gradeObjectCode = $gradeObject->gradeCode;

  //Get user object via line manager's employee number to obtain line manager's name
  $lineManager = $this->model->getUserDetailsByEmployeeNumber($_SESSION['user']['LineManager']);
  $lineManagerName = "" . $lineManager->firstName . " " . $lineManager->surname;

  //Get user object via line manager's employee number to obtain line manager's name
  $resourceManager = $this->model->getUserDetailsByEmployeeNumber($_SESSION['user']['ResourceManager']);
  $resourceManagerName = "" . $resourceManager->firstName . " " . $resourceManager->surname;

  //Get user object via reviewer manager's employee number to obtain reviewer manager's name
  $reviewerManager = $this->model->getUserDetailsByEmployeeNumber($_SESSION['user']['ReviewerManager']);
  $reviewerManagerName = "" . $reviewerManager->firstName . " " . $reviewerManager->surname;

  //Get base location object via base location ID and obtain base location details
  $baseLocation = $this->model->constructBaseLocationFromBaseLocationID($_SESSION['user']['BaseLocation']);
  $baseLocationName = $baseLocation->baseLocationName;
  $baseLocationCity = $baseLocation->city;
  $baseLocationCountry = $baseLocation->country;

  //Get business unit object via business unit ID and obtain business unit details
  $businessUnit = $this->model->constructBusinessUnitFromBusinessUnitID($_SESSION['user']['BusinessUnit']);
  $businessUnitName = $businessUnit->unit;

  //Get all project records, education records, skill records, and employment records associated with authenticated user
  $userSessionID = $_SESSION['user']['EmployeeNumber'];
  $projectResult = $this->model->getAllProjectsAssociatedWithThisUser($userSessionID);
  $educationResult = $this->model->getAllEducationAssociatedWithThisUser($userSessionID);
  $userToSkillsResult = $this->model->getAllSkillsAssociatedWithThisUser($userSessionID);
  $employmentResult = $this->model->getAllEmploymentAssociatedWithThisUser($userSessionID);

?>

    <main>
      <section id="user-profile-name" class="jumbotron">
        <div class="container">

          <?php
            echo '<h2>' . $_SESSION['user']['FirstName'] . ' ' . $_SESSION['user']['Surname'] . '</h2>';
            echo '<h3>' . $gradeJobTitle . '</h3>';
          ?>

        </div>
      </section>
    </main>

    <!-- Profile -->
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

          <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Profile</h4>
                <table class="table table-striped">

                  <?php
                  echo '<tr>';
                    echo '<th>Employee Number</th>';
                    echo '<td>' . $_SESSION['user']['EmployeeNumber'] . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th>Username</th>';
                    echo '<td>' . $_SESSION['user']['Username'] . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th>First Name</th>';
                    echo '<td>' . $_SESSION['user']['FirstName'] . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th>Surname</th>';
                    echo '<td>' . $_SESSION['user']['Surname'] . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th>Date of Birth</th>';
                    echo '<td>' . $_SESSION['user']['DateOfBirth'] . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th>Line Manager</th>';
                    echo '<td>' . $lineManagerName . '</td>';
                  echo '</tr>';

                  echo '<tr>';
                    echo '<th></th>';
                    echo '<td></td>';
                  echo '</tr>';
                  /*
                  ?>

                </table>
              </div>

              <div>
                <h4>Unit Information</h4>
                <table class="table table-striped">

                  <?php
                  */
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
            <!-- <div class="panel-footer">Panel Footer</div> -->
          </div>

        </div>
      </div>
    </div>

    <!-- Projects -->
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

          <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Projects</h4>

                <?php
                  if ($projectResult == 0) {
                    echo 'You have no projects to display. Add some projects in the \'Manage CV\' page.';
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
            <!-- <div class="panel-footer">Panel Footer</div> -->
          </div>

        </div>
      </div>
    </div>

    <!-- Education -->
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

          <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Education</h4>

                <?php
                  if ($educationResult == 0) {
                    echo 'You have no education records to display. Add some education records in the \'Manage CV\' page.';
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
            <!-- <div class="panel-footer">Panel Footer</div> -->
          </div>

        </div>
      </div>
    </div>

    <!-- Skills -->
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

          <div id="collapse4" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Skills</h4>

                <?php
                  if ($userToSkillsResult == 0) {
                    echo 'You have no skills to display. Add some skills in the \'Manage CV\' page.';
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
            <!-- <div class="panel-footer">Panel Footer</div> -->
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
                    echo 'You have no employment records to display. Add some employment records in the \'Manage CV\' page.';
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
            <!-- <div class="panel-footer">Panel Footer</div> -->
          </div>

        </div>
      </div>
    </div>

<?php
}
else {
?>

<div class="container">
  <h1>View My CV</h1>
  <p>You are currently not logged in. Please login to view your CV.</p>
</div>

<?php
}
?>
