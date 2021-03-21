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
* BaseLocation class to model a Base Location record
*
*/
class BaseLocation {
  private $baseLocationID = null;
  private $baseLocationName = null;
  private $city = null;
  private $country = null;

  /**
  * Class constructor
  * @param    $baseLocationID     The BaseLocationID of the record
  * @param    $baseLocationName   The name of the base location
  * @param    $city               The city of the base location
  * @param    $country            The country of the base location
  */
  public function __construct($baseLocationID, $baseLocationName, $city, $country) {
    $this->baseLocationID = $baseLocationID;
    $this->baseLocationName = $baseLocationName;
    $this->city = $city;
    $this->country = $country;
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
