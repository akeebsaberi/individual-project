<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

require 'vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $dompdf = new Dompdf();

  $userIDForGeneratingPDF = $this->model->getIDForGeneratePDF();
  $userObject = $this->model->getUserDetailsByEmployeeNumber($userIDForGeneratingPDF);

  //Get grade object and obtain corresponding job title and grade code
  $gradeObject = $this->model->constructGradeFromGradeID($userObject->grade);
  $gradeJobTitle = $gradeObject->jobTitle;
  $gradeObjectCode = $gradeObject->gradeCode;

  //Get user object via line manager's employee number to obtain line manager's name
  $lineManager = $this->model->getUserDetailsByEmployeeNumber($userObject->lineManager);
  $lineManagerName = "" . $lineManager->firstName . " " . $lineManager->surname;

  //Get user object via line manager's employee number to obtain line manager's name
  $resourceManager = $this->model->getUserDetailsByEmployeeNumber($userObject->resourceManager);
  $resourceManagerName = "" . $resourceManager->firstName . " " . $resourceManager->surname;

  //Get user object via reviewer manager's employee number to obtain reviewer manager's name
  $reviewerManager = $this->model->getUserDetailsByEmployeeNumber($userObject->reviewerManager);
  $reviewerManagerName = "" . $reviewerManager->firstName . " " . $reviewerManager->surname;

  //Get base location object via base location ID and obtain base location details
  $baseLocation = $this->model->constructBaseLocationFromBaseLocationID($userObject->baseLocation);
  $baseLocationName = $baseLocation->baseLocationName;
  $baseLocationCity = $baseLocation->city;
  $baseLocationCountry = $baseLocation->country;

  //Get business unit object via business unit ID and obtain business unit details
  $businessUnit = $this->model->constructBusinessUnitFromBusinessUnitID($userObject->businessUnit);
  $businessUnitName = $businessUnit->unit;

  //Get all project records, education records, skill records, and employment records associated with authenticated user
  $projectResult = $this->model->getAllProjectsAssociatedWithThisUser($userObject->employeeNumber);
  $educationResult = $this->model->getAllEducationAssociatedWithThisUser($userObject->employeeNumber);
  $userToSkillsResult = $this->model->getAllSkillsAssociatedWithThisUser($userObject->employeeNumber);
  $employmentResult = $this->model->getAllEmploymentAssociatedWithThisUser($userObject->employeeNumber);

  //Build PDF Content
  $html = '<h5 style="text-align:center;color:grey;text-shadow:2px 2px;">For Internal Use Only</h5>';
  $html .= '<br />';

  $html .= '<h3 style="text-align:center;">' . $userObject->firstName . ' ' . $userObject->surname . '</h3>';
  $html .= '<p style="text-align:center;margin=0px">Email Address: ' . $userObject->email . '</p>';
  $html .= '<p style="text-align:center;margin=0px">DoB: ' . $userObject->dateOfBirth . '</p>';

  $html .= '<h4>Personal Profile</h4>';

  $html .= '<p>Employee Number: ' .  $userObject->employeeNumber . '</p>';
  $html .= '<p>Base Location: ' .  $baseLocationName . ' - ' . $baseLocationCity . ', ' . $baseLocationCountry . '</p>';
  $html .= '<p>Business Unit: ' .  $businessUnitName . '</p>';
  $html .= '<p>Grade: ' .  $gradeJobTitle . ' (' . $gradeObjectCode . ')</p>';
  $html .= '<br />';
  $html .= '<p>Line Manager: ' .  $lineManagerName . '</p>';
  $html .= '<p>Resource Manager: ' .  $resourceManagerName . '</p>';
  $html .= '<p>Reviewer Manager: ' .  $reviewerManagerName . '</p>';
  $html .= '<br />';

  $html .= '<h4>Projects</h4>';

  if ($projectResult == 0) {
    $html .= '<p>' . $userObject->firstName . ' ' . $userObject->surname . ' currently has no projects.</p>';
  }
  else {
    foreach($projectResult as $row) {
      $html .= '<br />';

      $html .= '<p style="text-shadow:5px 5px;">' . $row->customer . ': ' . $row->projectName . '</p>';
      if($row->toDate == '0000-00-00') {
        $html .= '<p>(' . $row->fromDate . ' - To Date)</p>';
      }
      else {
        $html .= '<p>(' . $row->fromDate . ' - ' . $row->toDate . ')</p>';
      }
      $html .= '<p style="white-space:pre-wrap; word-wrap:break-word">' . $row->projectDescription . '</p>';
    }
  }

  $html .= '<br />';

  $html .= '<h4>Education</h4>';

  if ($educationResult == 0) {
    $html .= '<p>' . $userObject->firstName . ' ' . $userObject->surname . ' currently has no education records.</p>';
  }
  else {
    foreach($educationResult as $row) {
      $html .= '<br />';

      $html .= '<p style="text-shadow:5px 5px;">' . $row->subject . ': ' . $row->level . '</p>';
      if($row->toDate == '0000-00-00') {
        $html .= '<p>(' . $row->fromDate . ' - To Date)</p>';
      }
      else {
        $html .= '<p>(' . $row->fromDate . ' - ' . $row->toDate . ')</p>';
      }
    }
  }

  $html .= '<br />';

  $html .= '<h4>Skills</h4>';

  if ($userToSkillsResult == 0) {
    $html .= '<p>' . $userObject->firstName . ' ' . $userObject->surname . ' currently has no skill records.</p>';
  }
  else {
    foreach($userToSkillsResult as $row) {
      $html .= '<br />';

      $html .= '<p style="text-shadow:5px 5px;">' . $row->skillName . ' (' . $row->experienceInYears . ' years experience, ' . $row->competencyLevel . ')</p>';
    }
  }

  $html .= '<br />';

  $html .= '<h4>Employment</h4>';

  if ($employmentResult == 0) {
    $html .= '<p>' . $userObject->firstName . ' ' . $userObject->surname . ' currently has no employment records.</p>';
  }
  else {
    foreach($employmentResult as $row) {
      $html .= '<br />';

      $html .= '<p style="text-shadow:5px 5px;">' . $row->company . '</p>';
      if($row->toDate == '0000-00-00') {
        $html .= '<p>(' . $row->fromDate . ' - To Date)</p>';
      }
      else {
        $html .= '<p>(' . $row->fromDate . ' - ' . $row->toDate . ')</p>';
      }
    }
  }

  $filename = "Internal_CV_" . $userObject->firstName . "_" . $userObject->surname;

  $dompdf->loadHtml(html_entity_decode($html));
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();
  $dompdf->stream($filename, array("Attachment" => 0));
  //$dompdf->stream($filename);

}

?>
