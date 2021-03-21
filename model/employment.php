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
* Employment class to model an Employment record
*
*/
class Employment {
  private $employmentID = null;
  private $employeeNumber = null;
  private $company = null;
  private $fromDate = null;
  private $toDate = null;

  /**
  * Class constructor
  * @param    $employmentID     The EmploymentID of the record
  * @param    $employeeNumber   The Employee Number of the employee that the employment record belongs to
  * @param    $company          The name of the company
  * @param    $fromDate         The start date of employment
  * @param    $toDate           The end date of employment
  */
  public function __construct($employmentID, $employeeNumber, $company, $fromDate, $toDate) {
    $this->employmentID = $employmentID;
    $this->employeeNumber = $employeeNumber;
    $this->company = $company;
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
