
<?php
# An Account object corresponds to the columns in table savings
class Project {
  private $projectID = null;
  private $employeeNumber = null;
  private $projectName = null;
  private $customer = null;
  private $projectDescription = null;
  private $fromDate = null;
  private $toDate = null;

  # Creates a new account with the given name balance
  public function __construct($projectID, $employeeNumber, $projectName, $customer, $projectDescription, $fromDate, $toDate) {
    $this->projectID = $projectID;
    $this->employeeNumber = $employeeNumber;
    $this->projectName = $projectName;
    $this->customer = $customer;
    $this->projectDescription = $projectDescription;
    $this->fromDate = $fromDate;
    $this->toDate = $toDate;
  }

  # __get method
  public function __get($var){
	return $this->$var;
  }

}
?>
