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
* Grade class to model a Grade record
*
*/
class Grade {
  private $gradeID = null;
  private $gradeCode = null;
  private $jobTitle = null;

  /**
  * Class constructor
  * @param    $gradeID     The GradeID of the record
  * @param    $gradeCode   The code for the grade
  * @param    $jobTitle    The job title associated to the grade code
  */
  public function __construct($gradeID, $gradeCode, $jobTitle) {
    $this->gradeID = $gradeID;
    $this->gradeCode = $gradeCode;
    $this->jobTitle = $jobTitle;
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
