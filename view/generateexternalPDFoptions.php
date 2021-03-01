<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  $employeeIDToSearch = $this->model->getIDForGenerateExternalPDF();
  if (!isset($employeeIDToSearch)) {
    echo '<div class="container">';
    echo '<p>This operation has failed. Please try again.</p>';
    echo '</div>';
  }
  else {

    if (isset($_POST["externalPDFOptionSubmit"])) {
      if (!empty($_POST['checkboxList'])) {
        $this->model->processExternalPDFOptions($_POST["checkboxList"]);
      }
      else {
        $this->model->processExternalPDFOptions(0);
      }
      ?>
      <div class="container">
        <h1>Export to PDF (External CV)</h1>
        <div class="row">
          <div class="col-xs-12">
            <p>PDF selections saved. Please press the button below to generate your external CV.</p>
          </div>
          <div class="col-xs-12">
          <?php
          echo '<a href="?page=generateCVexternalPDF?' . $employeeIDToSearch . '" class="btn btn-info" role="button">Export to External PDF</a>';
          ?>
          </div>
        </div>
      </div>
      <?php
    }
    else {

      $userSearch = $this->model->getUserDetailsByEmployeeNumber($employeeIDToSearch);
      $employeeFullName = $userSearch->firstName . ' ' . $userSearch->surname;
      ?>

      <div class="container">
        <h1>Export to PDF (External CV)</h1>
        <div class="row">
          <div class="col-xs-12">
            <?php
              echo '<h4>Select the content you would like to include in the External CV to be generated for ' . $employeeFullName . '.</h4>'
            ?>
          </div>
        </div>

        <form action="" method="post">
          <div class="row">
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="baselocation" id="checkbox-base-location">
                <label class="form-check-label"  for="checkbox-base-location">Base Location</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="businessunit" id="checkbox-business-unit">
                <label class="form-check-label"  for="checkbox-business-unit">Business Unit</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="grade" id="checkbox-grade">
                <label class="form-check-label"  for="checkbox-grade">Grade</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="linemanager" id="checkbox-line-manager">
                <label class="form-check-label"  for="checkbox-line-manager">Line Manager</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="resourcemanager" id="checkbox-resource-manager">
                <label class="form-check-label"  for="checkbox-resource-manager">Resource Manager</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="reviewermanager" id="checkbox-reviewer-manager">
                <label class="form-check-label"  for="checkbox-reviewer-manager">Reviewer Manager</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="project" id="checkbox-projects">
                <label class="form-check-label"  for="checkbox-projects">Projects</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="education" id="checkbox-education">
                <label class="form-check-label"  for="checkbox-education">Education</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="skills" id="checkbox-skills">
                <label class="form-check-label"  for="checkbox-skills">Skills</label>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkboxList[]" value="employment" id="checkbox-employment">
                <label class="form-check-label"  for="checkbox-employment">Employment</label>
              </div>
            </div>
            <?php
              echo '<a href="?page=generateCVexternalPDF?"' . $employeeIDToSearch . '><input type="submit" class="form-control" name="externalPDFOptionSubmit" value="Submit"></a>';
            ?>
          </div>
        </form>
      </div>

      <?php
    }
  }
}

?>
