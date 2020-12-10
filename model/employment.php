<?php

//Employment object - corresponds to an employment record in the education table in the database
class Employment {
  private $employmentID = null;
  private $employeeNumber = null;
  private $company = null;
  private $fromDate = null;
  private $toDate = null;

  //Instantiates an employment object with the given employment details
  public function __construct($employmentID, $employeeNumber, $company, $fromDate, $toDate) {
    $this->employmentID = $employmentID;
    $this->employeeNumber = $employeeNumber;
    $this->company = $company;
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
  }

  # __get method
  public function __get($var){
	return $this->$var;
  }

}
?>
