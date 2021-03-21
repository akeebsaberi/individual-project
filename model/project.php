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
* Project class to model a Project record
*
*/
class Project {
  private $projectID = null;
  private $employeeNumber = null;
  private $projectName = null;
  private $customer = null;
  private $projectDescription = null;
  private $fromDate = null;
  private $toDate = null;

  /**
  * Class constructor
  * @param    $projectID            The ProjectID of the record
  * @param    $employeeNumber       The Employee Number of the employee that the employment record belongs to
  * @param    $projectName          The name of the project
  * @param    $customer             The name of the customer
  * @param    $projectDescription   The description of this project (usually long text)
  * @param    $fromDate             The start date of the project
  * @param    $toDate               The end date of the project
  */
  public function __construct($projectID, $employeeNumber, $projectName, $customer, $projectDescription, $fromDate, $toDate) {
    $this->projectID = $projectID;
    $this->employeeNumber = $employeeNumber;
    $this->projectName = $projectName;
    $this->customer = $customer;
    $this->projectDescription = $projectDescription;
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
