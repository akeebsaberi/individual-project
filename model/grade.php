<?php

//Grade object - corresponds to a grade in grades table in the database
class Grade {
  private $gradeID = null;
  private $gradeCode = null;
  private $jobTitle = null;

  //Instantiates a grade object with the given grade details
  public function __construct($gradeID, $gradeCode, $jobTitle) {
    $this->gradeID = $gradeID;
    $this->gradeCode = $gradeCode;
    $this->jobTitle = $jobTitle;
  }

  # __get method
  public function __get($var){
	   return $this->$var;
  }

}
?>
