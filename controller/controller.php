<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

include_once("model/model.php");
include_once("view/view.php");


/**
*
* Controller class controlling the flow of application component communication
*
* The Controller instantiates and controls interaction between Models and Views
*
*/
class Controller {

  /**
  * Class fields to store model and view class objects
  */
  public $model = null;
  public $view = null;

  /**
  * Class constructor
  *
  * Instantiates Model with PDO database connection object instantiated and connected wihin the Model
  * Instantiates View with Model object stored as a field in the View
  */
  function __construct() {
    $this->model = new Model("127.0.0.1", "individual_project_db", "root", "");
    $this->model->connect();
    $this->view = new View($this->model);
  }

  /**
  * Function to invoke a View to display
  *
  * Calls the display function in the View object
  */
  function invoke() {
    $this->view->display();
  }

}

?>
