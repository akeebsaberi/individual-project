<?php

//User object - corresponds to an authenticated user in the system
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

  //Instantiates a user object with the given user details on successful authentication
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

  # __get method
  public function __get($var){
	   return $this->$var;
  }


}
?>
