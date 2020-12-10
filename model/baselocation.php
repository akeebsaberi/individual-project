<?php

//BaseLocation object - corresponds to a base location in the baselocation table in the database
class BaseLocation {
  private $baseLocationID = null;
  private $baseLocationName = null;
  private $city = null;
  private $country = null;

  //Instantiates a base location object with the given base location details
  public function __construct($baseLocationID, $baseLocationName, $city, $country) {
    $this->baseLocationID = $baseLocationID;
    $this->baseLocationName = $baseLocationName;
    $this->city = $city;
    $this->country = $country;
  }

  # __get method
  public function __get($var){
	   return $this->$var;
  }

}
?>
