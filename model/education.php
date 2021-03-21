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
* Education class to model an Education record
*
*/
class Education {
  private $educationID = null;
  private $employeeNumber = null;
  private $subject = null;
  private $level = null;
  private $fromDate = null;
  private $toDate = null;

  /**
  * Class constructor
  * @param    $educationID      The EducationID of the record
  * @param    $employeeNumber   The Employee Number of the employee that the education record belongs to
  * @param    $subject          The name of the subject of the education
  * @param    $level            The level of education
  * @param    $fromDate         The start date of the education
  * @param    $toDate           The end date of the education
  */
  public function __construct($educationID, $employeeNumber, $subject, $level, $fromDate, $toDate) {
    $this->educationID = $educationID;
    $this->employeeNumber = $employeeNumber;
    $this->subject = $subject;
    $this->level = $level;
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
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
