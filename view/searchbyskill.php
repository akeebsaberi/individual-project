<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (($_SESSION['user']['IsAdmin'] != 1) || ($_SESSION['user']['IsResourceManager'] != 1)) {
    echo '<div class="container">';
    echo '<h1>Search For Employee</h1>';
    echo '<div class="row">';
    echo '<div class="col-xs-12">';
    echo '<p>You do not have permission to search for employees.</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
  else {

    if (isset($_POST["searchBySkill"])) {
      echo '<div class="container">';
      echo '<h1>Search For Employee</h1>';
      echo '</div>';
      if ((!isset($_POST['searchBySkillInput'])) || (!isset($_POST['minimumExperienceInYears'])) || (!isset($_POST['competencyleveloptions']))) {
        echo "<br />Please fill out all required fields";
      }
      else {
        $skillNameValue = htmlspecialchars($_POST['searchBySkillInput']);
        $minimumExperienceInYearsValue = $_POST['minimumExperienceInYears'];
        $competencyLevelValue = $_POST['competencyleveloptions'];
        $result = $this->model->getUserToSkillArrayByAdvancedSkillSearch($skillNameValue, $minimumExperienceInYearsValue, $competencyLevelValue);
        if ($result == 0) {
          echo '<div class="container"><p>No results found, please attempt a different skills search.</p></div>';
        }
        else {

          ?>
          <div class="container">
            <div class="col-xs-12">
              <table class="table table-striped">
                <tr>
                  <th>Employee Number</th>
                  <th>Username</th>
                  <th>First Name</th>
                  <th>Surname</th>
                  <th>Experience In Years</th>
                </tr>
          <?php

          $response = '';
          foreach ($result as $row) {
            $userEmployeeNumber = $row->employeeNumber;
            $userObject = $this->model->getUserDetailsByEmployeeNumber($userEmployeeNumber);
            $response .= '<tr>';
            $response .= '<td>' . $row->employeeNumber . '</td>';
            $response .= '<td><a href="?page=viewuserbyusername?' . $row->employeeNumber . '">' . $userObject->username . '</a></td>';
            $response .= '<td>' . $userObject->firstName . '</td>';
            $response .= '<td>' . $userObject->surname . '</td>';
            $response .= '<td>' . $row->experienceInYears . '</td>';
            $response .= '</tr>';
          }
          $response .= "</table>";
          //$response = '</div>';
          echo $response;
        }
        echo '</div>';
        echo '</div>';
      }
    }
    else {

  ?>

    <main>
      <div class="container">
        <h1>Search For Employee</h1>

        <form action="" method="post" id="search-by-skill">

          <div class="row">
            <div class="col-xs-12">
              <h4>Search By Skill</h4>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <input type="text" class="form-control" name="searchBySkillInput" value="" placeholder="Search By Skill..." id="skill-name-search" >
            </div>
          </div>

          <div class="row">
            <div id="response" class="col-xs-12">
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">
              <h4>Minimum Experience In Years</h4>
              <input type="number" class="form-control" name="minimumExperienceInYears">
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-md-6">
              <h4>Competency Level</h4>
            </div>
            <div class="col-xs-12">
              <input type="radio" name="competencyleveloptions" class="form-control-input" id="competency-level-1" value="1" checked>
              <label class="form-check-label" for="competency-level-1">Baseline</label>
            </div>
            <div class="col-xs-12">
              <input type="radio" name="competencyleveloptions" class="form-control-input" id="competency-level-2" value="2">
              <label class="form-check-label" for="competency-level-2">Progressing</label>
            </div>
            <div class="col-xs-12">
              <input type="radio" name="competencyleveloptions" class="form-control-input" id="competency-level-3" value="3">
              <label class="form-check-label" for="competency-level-3">Proficient</label>
            </div>
            <div class="col-xs-12">
              <input type="radio" name="competencyleveloptions" class="form-control-input" id="competency-level-4" value="4">
              <label class="form-check-label" for="competency-level-4">Experienced</label>
            </div>
            <div class="col-xs-12">
              <input type="radio" name="competencyleveloptions" class="form-control-input" id="competency-level-5" value="5">
              <label class="form-check-label" for="competency-level-5">Master</label>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12">
              <input type="submit" class="form-control" name="searchBySkill" value="Search By Skill">
            </div>
          </div>

        </form>
      </div>
    </main>

    <script type="text/javascript">
      $(document).ready(function(){
        $("#skill-name-search").keyup(function(){
          var query = $("#skill-name-search").val();

          if (query.length > 1) {
            $.ajax({
              url: '/individual-project/view/ajaxsearchbyskill.php',
              method:'POST',
              data: {
                search: 1,
                q: query
              },
              success: function(data) {
                $("#response").html(data);
              },
              dataType: 'text'
            });
          }
        });
        $(document).on('click', 'a', function(){
          $("#skill-name-search").val($(this).text());
          $("#response").html('');
        });
      });
    </script>

    <?php
    }
  }
}
?>
