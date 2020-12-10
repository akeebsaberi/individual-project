<?php

//UserToSkill object - corresponds to a usertoskill record in the usertoskill table in the database
class UserToSkill {
  private $userToSkillID = null;
  private $employeeNumber = null;
  private $skillID = null;
  private $skillName = null;
  private $skillType = null;
  private $isCoreSkill = null;
  private $competencyLevel = null;
  private $experienceInYears = null;

  //Instantiates an usertoskill object with the given usertoskill details
  public function __construct($userToSkillID, $employeeNumber, $skillID, $skillName, $skillType, $isCoreSkill, $competencyLevel, $experienceInYears) {
    $this->userToSkillID = $userToSkillID;
    $this->employeeNumber = $employeeNumber;
    $this->skillID = $skillID;
    $this->skillName = $skillName;
    $this->skillType = $skillType;
    $this->isCoreSkill = $isCoreSkill;
    $this->competencyLevel = $competencyLevel;
    $this->experienceInYears = $experienceInYears;
  }

  # __get method
    public function __get($var){
	return $this->$var;
  }

}
?>
