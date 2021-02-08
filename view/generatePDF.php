<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {

  $dompdf = new Dompdf();

  $userObject = $this->model->getUserDetailsByEmployeeNumber($_SESSION['user']['EmployeeNumber']);

  $html = '<h5 style="text-align:center;color:grey;text-shadow:2px 2px;">For Internal Use Only</h5>';
  $html .= '<br />';

  $html .= '<h3 style="text-align:center;">' . $userObject->firstName . ' ' . $userObject->surname . '</h3>';
  $html .= '<p style="text-align:center;margin=0px">Email Address: ' . $userObject->email . '</p>';
  $html .= '<p style="text-align:center;margin=0px">DoB: ' . $userObject->dateOfBirth . '</p>';

  $html .= '<h3>Personal Profile</h3>';
  $html .= '<table class="table table-bordered">';

  $html .= '<tr>';
  $html .= '<th>Employee Number</th>';
  $html .= '<td>' . $_SESSION['user']['EmployeeNumber'] . '</td>';
  $html .= '</tr>';

  $html .= '<tr>';
  $html .= '<th>Grade</th>';
  $html .= '<td>' . $_SESSION['user']['Grade'] . '</td>';
  $html .= '</tr>';

  $html .= '</table>';

  $filename = "Internal_CV_" . $userObject->firstName . "_" . $userObject->surname;

  $dompdf->loadHtml(html_entity_decode($html));
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();
  $dompdf->stream($filename, array("Attachment" => 0));

}

?>
