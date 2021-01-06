<?php

#start session if it has not been started already
if (!isset($_SESSION)) {
  session_start();
}


if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
  if (isset($_POST["addNewSkill"])) {
    if((!isset($_POST['userToSkillID'])) || (!isset($_POST['employeeNumber'])) || (!isset($_POST['skillName'])) || (!isset($_POST['coreskillboolean'])) || (!isset($_POST['competencyleveloptions'])) || (!isset($_POST['experienceInYears']))) {
      echo "<br />Please fill out all required fields";
    }
    else {
      $isCoreSkillRadioValue = $_POST["coreskillboolean"];
      $competencyLevelRadioValue = $_POST["competencyleveloptions"];
      $result = $this->model->addNewUserToSkillToDatabase($_POST["userToSkillID"], $_POST["employeeNumber"], $_POST["skillName"], $isCoreSkillRadioValue, $competencyLevelRadioValue, $_POST["experienceInYears"]);
    }
  }

  $maximumUserToSkillID = $this->model->getUserToSkillWithHighestID();
  $newUserToSkillID = $maximumUserToSkillID + 1;

?>

<main>
  <div class="container">
    <h1>Add Skill</h1>
    <form action="" method="post">

      <?php
        echo '<input type="hidden" class="form-control" name="userToSkillID" value="' . $newUserToSkillID . '">';
        echo '<input type="hidden" class="form-control" name="employeeNumber" value="' . $_SESSION['user']['EmployeeNumber'] . '">';
      ?>

      <div class="row">
        <div class="col-xs-12">
          <h4>Skill Name</h4>
          <input type="text" class="form-control" name="skillName" id="skill-name-search">
        </div>
      </div>

      <div class="row">
        <div id="response" class="col-xs-12">
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <h4>Core Skill?</h4>
        </div>
        <div class="col-xs-4">
          <input type="radio" name="coreskillboolean" class="form-control-input" id="is-a-core-skill" value="1" checked>
          <label class="form-check-label" for="is-a-core-skill">This is a core skill</label>
        </div>
        <div class="col-xs-4">
          <input type="radio" name="coreskillboolean" class="form-control-input" id="is-not-a-core-skill" value="0">
          <label class="form-check-label" for="is-not-a-core-skill">This is not a core skill</label>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
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
          <h4>Experience in Years</h4>
          <input type="number" class="form-control" name="experienceInYears">
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <input type="submit" class="form-control" name="addNewSkill" value="Add Skill">
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
          url: '/individual-project/view/ajaxskillname.php',
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

?>
