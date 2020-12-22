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
      if (strpos($_GET["page"], 'editproject') !== false) {
        $queryString = $_GET["page"];
        $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
        $extractedQueryString = $extractedQueryString + 0;
        $this->model->setProjectToEdit($extractedQueryString);
        $this->page = "view/editproject.php";
      }
      else if (strpos($_GET["page"], 'editeducation') !== false) {
        $queryString = $_GET["page"];
        $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
        $extractedQueryString = $extractedQueryString + 0;
        $this->model->setEducationToEdit($extractedQueryString);
        $this->page = "view/editeducation.php";
      }
      else if (strpos($_GET["page"], 'editemployment') !== false) {
        $queryString = $_GET["page"];
        $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
        $extractedQueryString = $extractedQueryString + 0;
        $this->model->setEmploymentToEdit($extractedQueryString);
        $this->page = "view/editemployment.php";
      }
      else {
        $this->page = "view/" . $_GET["page"] . ".php";
      }
      require($this->page);
    }
  }

}

?>
