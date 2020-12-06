<?php

include_once("model/model.php");
include_once("view/view.php");

class Controller {

  //Fields for model and view objects
  public $model = null;
  public $view = null;

  //Constructor for Controller class
  function __construct() {
    $this->model = new Model("127.0.0.1", "individual_project_db", "root", "");
    $this->model->connect();
    $this->view = new View($this->model);
  }

  //Invoke function to display a view page
  function invoke() {
    $this->view->display();
  }

}

?>
