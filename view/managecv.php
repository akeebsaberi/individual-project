<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $userSessionID = $_SESSION['user']['EmployeeNumber'];
  $projectResult = $this->model->getAllProjectsAssociatedWithThisUser($userSessionID);
  $educationResult = $this->model->getAllEducationAssociatedWithThisUser($userSessionID);
  $userToSkillsResult = $this->model->getAllSkillsAssociatedWithThisUser($userSessionID);
  $employmentResult = $this->model->getAllEmploymentAssociatedWithThisUser($userSessionID);

?>

  <main>

    <div class="container">
      <h1>Manage Your CV</h1>
    </div>

    <div class="container">
      <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" href="#collapse1">
                <h3>Manage Projects</h3>
              </a>
            </h4>
          </div>

          <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Projects</h4>

                <?php
                if ($projectResult == 0) {
                  echo '<p>You currently have no projects to display. Add a project to get started.</p>';
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
                    <th></th>
                  </tr>
                  <?php
                  foreach($projectResult as $row) {
                    echo '<tr>';
                    echo '<td>' . $row->projectName . '</td>';
                    echo '<td>' . $row->customer . '</td>';
                    echo '<td style="white-space:pre-wrap; word-wrap:break-word">' . $row->projectDescription . '</td>';
                    echo '<td>' . $row->fromDate . '</td>';
                    echo '<td>' . $row->toDate . '</td>';
                    echo '<td><a href="?page=editproject?' . $row->projectID . '">Edit</a></td>';
                    echo '</tr>';
                  }
                  echo '</table>';
                }
                ?>
                <a href="?page=addnewproject" class="btn btn-primary active" role="button" aria-pressed="true">Add a Project</a>
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
              <a data-toggle="collapse" href="#collapse2">
                <h3>Manage Education</h3>
              </a>
            </h4>
          </div>

          <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Education</h4>

                <?php
                if ($educationResult == 0) {
                  echo '<p>You currently have no education records to display. Add an education record to get started.</p>';
                }
                else {
                ?>
                  <table class="table table-striped">
                    <tr>
                      <th>Subject</th>
                      <th>Level</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th></th>
                    </tr>
                  <?php
                  foreach($educationResult as $row) {
                    echo '<tr>';
                    echo '<td>' . $row->subject . '</td>';
                    echo '<td>' . $row->level . '</td>';
                    echo '<td>' . $row->fromDate . '</td>';
                    echo '<td>' . $row->toDate . '</td>';
                    echo '<td><a href="?page=editeducation?' . $row->educationID . '">Edit</a></td>';
                    echo '</tr>';
                  }
                  echo '</table>';
                }
                ?>
                <a href="?page=addneweducation" class="btn btn-primary active" role="button" aria-pressed="true">Add Education</a>
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
              <a data-toggle="collapse" href="#collapse3">
                <h3>Manage Skills</h3>
              </a>
            </h4>
          </div>

          <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Skills</h4>

                <?php
                if ($userToSkillsResult == 0) {
                  echo '<p>You currently have no skills to display. Add a skill to get started.</p>';
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
                      <th></th>
                    </tr>
                    <?php
                    foreach($userToSkillsResult as $row) {
                      echo '<tr>';
                      echo '<td>' . $row->skillName . '</td>';
                      echo '<td>' . $row->skillType . '</td>';
                      echo '<td>' . $row->isCoreSkill . '</td>';
                      echo '<td>' . $row->competencyLevel . '</td>';
                      echo '<td>' . $row->experienceInYears . '</td>';
                      echo '<td><a href="?page=editusertoskill?' . $row->userToSkillID . '">Edit</a></td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </table>
                <a href="?page=addnewskill" class="btn btn-primary active" role="button" aria-pressed="true">Add Skill</a>
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
              <a data-toggle="collapse" href="#collapse4">
                <h3>Manage Employment</h3>
              </a>
            </h4>
          </div>

          <div id="collapse4" class="panel-collapse collapse">
            <div class="panel-body">
              <div>
                <h4>Employment</h4>

                <?php
                if ($employmentResult == 0) {
                  echo '<p>You currently have no skills to display. Add a skill to get started.</p>';
                }
                else {
                ?>
                  <table class="table table-striped">
                    <tr>
                      <th>Company</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th></th>
                    </tr>
                  <?php
                  foreach($employmentResult as $row) {
                    echo '<tr>';
                    echo '<td>' . $row->company . '</td>';
                    echo '<td>' . $row->fromDate . '</td>';
                    echo '<td>' . $row->toDate . '</td>';
                    echo '<td><a href="?page=editemployment?' . $row->employmentID . '">Edit</a></td>';
                    echo '</tr>';
                  }
                }
                ?>
              </table>
              <a href="?page=addnewemployment" class="btn btn-primary active" role="button" aria-pressed="true">Add Employment</a>
            </div>

          </div>
          <!-- <div class="panel-footer">Panel Footer</div> -->
        </div>

      </div>
    </div>
  </div>

  </main>


<?php
}


else {

?>
<div class="container">
  <h1>Manage My CV</h1>
  <p>You are currently not logged in. Please login to manage your CV.</p>
</div>

<?php
}
?>
