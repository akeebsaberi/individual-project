<?php

class View {

  public $page = null;
  public $model = null;

  //constructor for View class
  function __construct($model) {
    $this->model = $model;
  }

  //display function to display a webpage
  function display() {
    require_once("menu.php");
    if (isset($_GET["page"])) {
      $this->page = "view/" . $_GET["page"] . ".php";
      require($this->page);
    }
  }

}

?>
