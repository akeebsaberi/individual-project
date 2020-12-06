<?php

class View {

  public $page = null;
  public $model = null;

  //Constructor for View class
  function __construct($model) {
    $this->model = $model;
  }

  //Display function to display a webpage
  function display() {
    require_once("menu.php");
    if (isset($_GET["page"])) {
      $this->page = "view/" . $_GET["page"] . ".php";
      require($this->page);
    }
  }

}

?>
