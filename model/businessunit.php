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
* BusinessUnit class to model a Business Unit record
*
*/
class BusinessUnit {
  private $businessUnitID = null;
  private $unit = null;

  /**
  * Class constructor
  * @param    $businessUnitID     The BusinessUnitID of the record
  * @param    $unit               The name of the business unit
  */
  public function __construct($businessUnitID, $unit) {
    $this->businessUnitID = $businessUnitID;
    $this->unit = $unit;
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
