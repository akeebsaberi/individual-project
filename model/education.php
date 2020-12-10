<?php

//Education object - corresponds to an education record in the education table in the database
class Education {
  private $educationID = null;
  private $employeeNumber = null;
  private $subject = null;
  private $level = null;
  private $fromDate = null;
  private $toDate = null;

  //Instantiates an education object with the given education details
  public function __construct($educationID, $employeeNumber, $subject, $level, $fromDate, $toDate) {
    $this->educationID = $educationID;
    $this->employeeNumber = $employeeNumber;
    $this->subject = $subject;
    $this->level = $level;
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
  }

  # __get method
  public function __get($var){
	   return $this->$var;
  }

}
?>
