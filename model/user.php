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
* User class to model a User record
*
*/
class User {
  private $employeeNumber = null;
  private $firstName = null;
  private $surname = null;
  private $username = null;
  private $password = null;
  private $email = null;
  private $isResourceManager = null;
  private $isAdmin = null;
  private $dateOfBirth = null;
  private $baseLocation = null;
  private $lineManager = null;
  private $reviewerManager = null;
  private $resourceManager = null;
  private $businessUnit = null;
  private $grade = null;

  /**
  * Class constructor
  * @param    $employeeNumber       The ProjectID of the record
  * @param    $firstName            The Employee Number of the employee that the employment record belongs to
  * @param    $surname              The name of the project
  * @param    $username             The name of the customer
  * @param    $password             The description of this project (usually long text)
  * @param    $email                The start date of the project
  * @param    $isResourceManager    The
  * @param    $isAdmin              The ProjectID of the record
  * @param    $dateOfBirth          The Employee Number of the employee that the employment record belongs to
  * @param    $baseLocation         The name of the project
  * @param    $lineManager          The name of the customer
  * @param    $reviewerManager      The description of this project (usually long text)
  * @param    $resourceManager      The start date of the project
  * @param    $businessUnit         The end date of the project
  * @param    $grade                The end date of the project
  */
  public function __construct($employeeNumber, $firstName, $surname, $username, $password, $email, $isResourceManager, $isAdmin, $dateOfBirth, $baseLocation, $lineManager, $reviewerManager, $resourceManager, $businessUnit, $grade) {
    $this->employeeNumber = $employeeNumber;
    $this->firstName = $firstName;
    $this->surname = $surname;
    $this->username = $username;
    $this->password = $password;
    $this->email = $email;
    $this->isResourceManager = $isResourceManager;
    $this->isAdmin = $isAdmin;
    $this->dateOfBirth = $dateOfBirth;
    $this->baseLocation = $baseLocation;
    $this->lineManager = $lineManager;
    $this->reviewerManager = $reviewerManager;
    $this->resourceManager = $resourceManager;
    $this->businessUnit = $businessUnit;
    $this->grade = $grade;
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
