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
* View class to display View webpages based on user interaction and Model manipulation and accessing, as coordinated by the Controller
*
* The View displays the right page based on the coordination of the Controller
*
*/
class View {

  /**
  * Class fields to store the displayed page name and Model class object respectively
  */
  public $page = null;
  public $model = null;

  /**
  * Class constructor
  *
  * @param    $model    Model object to be stored in the model field
  */
  function __construct($model) {
    $this->model = $model;
  }

  /**
  * Function to display the correct view given the GET request for a page or application state
  */
  function display() {

    if ((isset($_GET["page"])) && (strpos($_GET["page"], 'generatePDF') !== false)) {
      $queryString = $_GET["page"];
      $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
      $extractedQueryString = $extractedQueryString + 0;
      $this->model->setIDForGeneratePDF($extractedQueryString);
      $this->page = "view/generatePDF.php";
      require($this->page);
    }
    else if ((isset($_GET["page"])) && (strpos($_GET["page"], 'generateCVexternalPDF') !== false)) {
      $queryString = $_GET["page"];
      $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
      $extractedQueryString = $extractedQueryString + 0;
      $this->model->setIDForGenerateExternalPDF($extractedQueryString);
      $this->page = "view/generateCVexternalPDF.php";
      require($this->page);
    }
    else if (!isset($_GET["page"])) {
      require_once("menu.php");
    }
    else {
      require_once("menu.php");
      if (isset($_GET["page"])) {
        if (strpos($_GET["page"], 'editproject') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setProjectToEdit($extractedQueryString);
          $this->page = "view/editproject.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'editeducation') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setEducationToEdit($extractedQueryString);
          $this->page = "view/editeducation.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'editusertoskill') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setUserToSkillToEdit($extractedQueryString);
          $this->page = "view/editusertoskill.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'editemployment') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setEmploymentToEdit($extractedQueryString);
          $this->page = "view/editemployment.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'deleteproject') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setProjectToDelete($extractedQueryString);
          $this->page = "view/deleteproject.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'deleteeducation') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setEducationToDelete($extractedQueryString);
          $this->page = "view/deleteeducation.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'deleteusertoskill') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setUserToSkillToDelete($extractedQueryString);
          $this->page = "view/deleteusertoskill.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'deleteemployment') !== false) {
          $queryString = $_GET["page"];
          $extractedQueryString = substr($queryString, strpos($queryString, "?") + 1);
          $extractedQueryString = $extractedQueryString + 0;
          $this->model->setEmploymentToDelete($extractedQueryString);
          $this->page = "view/deleteemployment.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'viewuserbyusername') !== false) {
          $queryString = $_GET["page"];
          $queryString = substr($queryString, strpos($queryString, "?") + 1);
          $queryString = $queryString + 0;
          $this->model->setEmployeeIDToView($queryString);
          $this->page = "view/viewuserbyusername.php";
          require($this->page);
        }
        else if (strpos($_GET["page"], 'generateexternalPDFoptions') !== false) {
          $queryString = $_GET["page"];
          $queryString = substr($queryString, strpos($queryString, "?") + 1);
          $queryString = $queryString + 0;
          $this->model->setIDForGenerateExternalPDF($queryString);
          $this->page = "view/generateexternalPDFoptions.php";
          require($this->page);
        }
        else {
          $this->page = "view/" . $_GET["page"] . ".php";
          require($this->page);
        }
      }
    }
  }

}

?>
