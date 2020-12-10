<?php

//BusinessUnit object - corresponds to a business unit in the baselocation table in the database
class BusinessUnit {
  private $businessUnitID = null;
  private $unit = null;

  //Instantiates a business unit object with the given business unit details
  public function __construct($businessUnitID, $unit) {
    $this->businessUnitID = $businessUnitID;
    $this->unit = $unit;
  }

  # __get method
  public function __get($var){
	   return $this->$var;
  }

}
?>
