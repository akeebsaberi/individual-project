<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

/**
*
* UserToSkill class to model a UserToSkill record
*
*/
class UserToSkill {
  private $userToSkillID = null;
  private $employeeNumber = null;
  private $skillID = null;
  private $skillName = null;
  private $skillType = null;
  private $isCoreSkill = null;
  private $competencyLevel = null;
  private $experienceInYears = null;

  /**
  * Class constructor
  * @param    $userToSkillID        The UserToSkillID of the record
  * @param    $employeeNumber       The Employee Number of the employee that the usertoskill record belongs to
  * @param    $skillID              The SkillID for this usertoskill record
  * @param    $skillName            The name of the skill
  * @param    $skillType            The type of skill
  * @param    $isCoreSkill          Whether this is a core skill or not
  * @param    $competencyLevel      The competency level of the employee with this skill
  * @param    $experienceInYears    The experience in years this employee has with this skill
  */
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

  /**
  * Magic accessor function
  *
  * @return    $var    The name of the field to be returned
  */
  public function __get($var){
	   return $this->$var;
  }

}
?>
