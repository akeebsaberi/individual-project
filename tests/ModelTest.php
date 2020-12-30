<?php

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase {

  //Login and Logout Unit Tests

  //Test 1
  public function testGetUserByUsernameAndPassword_validUser() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //16, admin, test, unittest, unittestpassword, 1, 1, 2000-01-01, 1, 1, 2, 3, 1, 1

    $testUser1 = $testModel->getUserByUsernameAndPassword('unittest', 'unittestpassword');

    $this->assertEquals(16, $testUser1->employeeNumber);
    $this->assertEquals('admin', $testUser1->firstName);
    $this->assertEquals('test', $testUser1->surname);
    $this->assertEquals('unittest', $testUser1->username);
    $this->assertTrue(password_verify('unittestpassword', $testUser1->password));
    $this->assertEquals('unit.test@unittest.com', $testUser1->email);
    $this->assertEquals(1, $testUser1->isResourceManager);
    $this->assertEquals(1, $testUser1->isAdmin);
    $this->assertEquals('2000-01-01', $testUser1->dateOfBirth);
    $this->assertEquals(1, $testUser1->baseLocation);
    $this->assertEquals(1, $testUser1->lineManager);
    $this->assertEquals(2, $testUser1->reviewerManager);
    $this->assertEquals(3, $testUser1->resourceManager);
    $this->assertEquals(1, $testUser1->businessUnit);
    $this->assertEquals(1, $testUser1->grade);
  }

  public function testGetUserByUsernameAndPassword_validUserSessionVariables() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel2 = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel2->connect();

    $testUser2 = $testModel2->getUserByUsernameAndPassword('unittest', 'unittestpassword');

    $this->assertEquals(16, $_SESSION['user']['EmployeeNumber']);
    $this->assertEquals('admin', $_SESSION['user']['FirstName']);
    $this->assertEquals('test', $_SESSION['user']['Surname']);
    $this->assertEquals('unittest', $_SESSION['user']['Username']);
    $this->assertTrue(password_verify('unittestpassword', $_SESSION['user']['Password']));
    $this->assertEquals('unit.test@unittest.com', $_SESSION['user']['Email']);
    $this->assertEquals(1, $_SESSION['user']['IsResourceManager']);
    $this->assertEquals(1, $_SESSION['user']['IsAdmin']);
    $this->assertEquals('2000-01-01', $_SESSION['user']['DateOfBirth']);
    $this->assertEquals(1, $_SESSION['user']['BaseLocation']);
    $this->assertEquals(1, $_SESSION['user']['LineManager']);
    $this->assertEquals(2, $_SESSION['user']['ReviewerManager']);
    $this->assertEquals(3, $_SESSION['user']['ResourceManager']);
    $this->assertEquals(1, $_SESSION['user']['BusinessUnit']);
    $this->assertEquals(1, $_SESSION['user']['Grade']);
  }

  public function testGetUserByUsernameAndPassword_invalidUsername() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('invalidusername', 'unittestpassword');

    //User does not exist, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_invalidPassword() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('unittest', 'invalidpassword');

    //Username or password is incorrect, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_invalidUsernameAndPassword() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('invalidusername', 'invalidpassword');

    //User does not exist, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_emptyUsernameAndPassword() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('', '');

    //User does not exist, please try again.
    $this->assertNull($testUser);
  }

  /*
  public function testLogout() {
    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('unittest', 'unittestpassword');

    $this->assertEquals(16, $_SESSION['user']['EmployeeNumber']);
    $this->assertEquals('admin', $_SESSION['user']['FirstName']);
    $this->assertEquals('test', $_SESSION['user']['Surname']);
    $this->assertEquals('unittest', $_SESSION['user']['Username']);
    $this->assertTrue(password_verify('unittestpassword', $_SESSION['user']['Password']));
    $this->assertEquals(1, $_SESSION['user']['IsResourceManager']);
    $this->assertEquals(1, $_SESSION['user']['IsAdmin']);
    $this->assertEquals('2000-01-01', $_SESSION['user']['DateOfBirth']);
    $this->assertEquals(1, $_SESSION['user']['BaseLocation']);
    $this->assertEquals(1, $_SESSION['user']['LineManager']);
    $this->assertEquals(2, $_SESSION['user']['ReviewerManager']);
    $this->assertEquals(3, $_SESSION['user']['ResourceManager']);
    $this->assertEquals(1, $_SESSION['user']['BusinessUnit']);
    $this->assertEquals(1, $_SESSION['user']['Grade']);

    include_once 'view\logout.php';

    $this->assertNull($_SESSION['user']['EmployeeNumber']);
    $this->assertNull($_SESSION['user']['FirstName']);
    $this->assertNull($_SESSION['user']['Surname']);
    $this->assertNull($_SESSION['user']['Username']);
    $this->assertNull($_SESSION['user']['Password']);
    $this->assertNull($_SESSION['user']['IsResourceManager']);
    $this->assertNull($_SESSION['user']['IsAdmin']);
    $this->assertNull($_SESSION['user']['DateOfBirth']);
    $this->assertNull($_SESSION['user']['BaseLocation']);
    $this->assertNull($_SESSION['user']['LineManager']);
    $this->assertNull($_SESSION['user']['ReviewerManager']);
    $this->assertNull($_SESSION['user']['ResourceManager']);
    $this->assertNull($_SESSION['user']['BusinessUnit']);
    $this->assertNull($_SESSION['user']['Grade']);
  }
  */

  //View My CV Unit Tests

  public function testGetUserByEmployeeNumber_validEmployeeNumber() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserDetailsByEmployeeNumber(16);

    $this->assertEquals(16, $testUser->employeeNumber);
    $this->assertEquals('admin', $testUser->firstName);
    $this->assertEquals('test', $testUser->surname);
    $this->assertEquals('unittest', $testUser->username);
    $this->assertTrue(password_verify('unittestpassword', $testUser->password));
    $this->assertEquals('unit.test@unittest.com', $testUser->email);
    $this->assertEquals(1, $testUser->isResourceManager);
    $this->assertEquals(1, $testUser->isAdmin);
    $this->assertEquals('2000-01-01', $testUser->dateOfBirth);
    $this->assertEquals(1, $testUser->baseLocation);
    $this->assertEquals(1, $testUser->lineManager);
    $this->assertEquals(2, $testUser->reviewerManager);
    $this->assertEquals(3, $testUser->resourceManager);
    $this->assertEquals(1, $testUser->businessUnit);
    $this->assertEquals(1, $testUser->grade);
  }

  public function testGetUserByEmployeeNumber_invalidEmployeeNumber() {
    require_once 'model\user.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserDetailsByEmployeeNumber(1000);

    $this->assertNull($testUser);
  }

  public function testConstructGradeFromGradeID_validGradeID() {
    require_once 'model\grade.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be ID = 13, Grade = P6, Job Title = Lead Project Manager
    $testGrade = $testModel->constructGradeFromGradeID(13);

    $this->assertEquals(13, $testGrade->gradeID);
    $this->assertEquals('P6', $testGrade->gradeCode);
    $this->assertEquals('Lead Project Manager', $testGrade->jobTitle);
  }

  public function testConstructGradeFromGradeID_invalidGradeID() {
    require_once 'model\grade.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be null
    $testGrade = $testModel->constructGradeFromGradeID(100);

    $this->assertNull($testGrade);
  }

  public function testConstructBaseLocationFromBaseLocationID_validBaseLocationID() {
    require_once 'model\baselocation.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be ID = 2, Location Name = Canary Wharf, City = London, Country = England
    $testBaseLocation = $testModel->constructBaseLocationFromBaseLocationID(2);

    $this->assertEquals(2, $testBaseLocation->baseLocationID);
    $this->assertEquals('Canary Wharf', $testBaseLocation->baseLocationName);
    $this->assertEquals('London', $testBaseLocation->city);
    $this->assertEquals('England', $testBaseLocation->country);
  }

  public function testConstructBaseLocationFromBaseLocationID_invalidBaseLocationID() {
    require_once 'model\baselocation.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be null
    $testBaseLocation = $testModel->constructBaseLocationFromBaseLocationID(100);

    $this->assertNull($testBaseLocation);
  }

  public function testConstructBusinessUnitFromBusinessUnitID_validBusinessUnitID() {
    require_once 'model\businessunit.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be ID = 1, Unit = Front End Webapp Development
    $testBusinessUnit = $testModel->constructBusinessUnitFromBusinessUnitID(1);

    $this->assertEquals(1, $testBusinessUnit->businessUnitID);
    $this->assertEquals('Front End Webapp Development', $testBusinessUnit->unit);
  }

  public function testConstructBusinessUnitFromBusinessUnitID_invalidBusinessUnitID() {
    require_once 'model\businessunit.php';
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //Should be null
    $testBusinessUnit = $testModel->constructBusinessUnitFromBusinessUnitID(100);

    $this->assertNull($testBusinessUnit);
  }

  public function testGetAllProjectsAssociatedWithThisUser_validCount() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //test for one project row -> 'Unit Test Project'
    $testProjectResults = $testModel->getAllProjectsAssociatedWithThisUser(16);
    $testProjectCount = 0;
    foreach($testProjectResults as $row) {
      $testProjectCount++;
    }
    $this->assertEquals(1, $testProjectCount);
  }

  public function testGetAllEducationAssociatedWithThisUser_validCount() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //test for one project row -> 'Unit Test Project'
    $testEducationResults = $testModel->getAllEducationAssociatedWithThisUser(16);
    $testEducationCount = 0;
    foreach($testEducationResults as $row) {
      $testEducationCount++;
    }
    $this->assertEquals(2, $testEducationCount);
  }

  public function testGetAllEmploymentAssociatedWithThisUser_validCount() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //test for one project row -> 'Unit Test Project'
    $testEmploymentResults = $testModel->getAllEmploymentAssociatedWithThisUser(16);
    $testEmploymentCount = 0;
    foreach($testEmploymentResults as $row) {
      $testEmploymentCount++;
    }
    $this->assertEquals(3, $testEmploymentCount);
  }

  public function testGetProjectWithHighestID_validTest() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestProjectID = $testModel->getProjectWithHighestID();

    $this->assertEquals(8, $testHighestProjectID);
  }

  public function testGetEducationWithHighestID_validTest() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestEducationID = $testModel->getEducationWithHighestID();

    $this->assertEquals(5, $testHighestEducationID);
  }

  public function testGetEmploymentWithHighestID_validTest() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestEmploymentID = $testModel->getEmploymentWithHighestID();

    $this->assertEquals(5, $testHighestEmploymentID);
  }

  public function testAddNewProjectToDatabase_validProject() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->addNewProjectToDatabase(9, 16, "Unit Test Project 2", "Unit Test Project Customer", "This is the project test being added as part of the unit testing. Please delete this record from the database afterwards.", "2016-01-01", "2017-01-01");

    $testHighestProjectID = $testModel->getProjectWithHighestID();

    $this->assertEquals(9, $testHighestProjectID);
  }

  public function testAddNewEducationToDatabase_validEducation() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->addNewEducationToDatabase(6, 16, "Unit Test Education 3", "Unit Test Level", "2016-01-01", "2017-01-01");

    $testHighestEducationID = $testModel->getEducationWithHighestID();

    $this->assertEquals(6, $testHighestEducationID);
  }

  public function testAddNewEmploymentToDatabase_validEmployment() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->addNewEmploymentToDatabase(6, 16, "Unit Test Company 4", "2016-01-01", "2017-01-01");

    $testHighestEmploymentID = $testModel->getEmploymentWithHighestID();

    $this->assertEquals(6, $testHighestEmploymentID);
  }

  public function testEditProjectDetails_validEdit() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->editProjectDetails(9, "Edited Unit Test Project 2", "Edited Unit Test Project Customer", "This is the project test being edited as part of the unit testing. Please delete this record from the database afterwards.", "2016-02-03", "2017-02-03");

    $editedTestProject = $testModel->getProjectToBeEdited(9);

    $this->assertEquals(9, $editedTestProject->projectID);
    $this->assertEquals(16, $editedTestProject->employeeNumber);
    $this->assertEquals("Edited Unit Test Project 2", $editedTestProject->projectName);
    $this->assertEquals("Edited Unit Test Project Customer", $editedTestProject->customer);
    $this->assertEquals("This is the project test being edited as part of the unit testing. Please delete this record from the database afterwards.", $editedTestProject->projectDescription);
    $this->assertEquals("2016-02-03", $editedTestProject->fromDate);
    $this->assertEquals("2017-02-03", $editedTestProject->toDate);
  }

  public function testEditEducationDetails_validEdit() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->editEducationDetails(6, "Edited Unit Test Education 3", "Edited Unit Test Level", "2016-02-03", "2017-02-03");

    $editedTestEducation = $testModel->getEducationToBeEdited(6);

    $this->assertEquals(6, $editedTestEducation->educationID);
    $this->assertEquals(16, $editedTestEducation->employeeNumber);
    $this->assertEquals("Edited Unit Test Education 3", $editedTestEducation->subject);
    $this->assertEquals("Edited Unit Test Level", $editedTestEducation->level);
    $this->assertEquals("2016-02-03", $editedTestEducation->fromDate);
    $this->assertEquals("2017-02-03", $editedTestEducation->toDate);
  }

  public function testEditEmploymentDetails_validEdit() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testModel->editEmploymentDetails(6, "Edited Unit Test Company 4", "2016-02-03", "2017-02-03");

    $editedTestEmployment = $testModel->getEmploymentToBeEdited(6);

    $this->assertEquals(6, $editedTestEmployment->employmentID);
    $this->assertEquals(16, $editedTestEmployment->employeeNumber);
    $this->assertEquals("Edited Unit Test Company 4", $editedTestEmployment->company);
    $this->assertEquals("2016-02-03", $editedTestEmployment->fromDate);
    $this->assertEquals("2017-02-03", $editedTestEmployment->toDate);
  }

  public function testDeleteProjectByProjectID_validDelete() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestProjectID = $testModel->getProjectWithHighestID();

    $this->assertEquals(9, $testHighestProjectID);

    $testModel->deleteProjectByProjectID(9);

    $testHighestProjectID = $testModel->getProjectWithHighestID();

    $this->assertEquals(8, $testHighestProjectID);
  }

  public function testDeleteEducationByEducationID_validDelete() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestEducationID = $testModel->getEducationWithHighestID();

    $this->assertEquals(6, $testHighestEducationID);

    $testModel->deleteEducationByEducationID(6);

    $testHighestEducationID = $testModel->getEducationWithHighestID();

    $this->assertEquals(5, $testHighestEducationID);
  }

  public function testDeleteEmploymentByEmploymentID_validDelete() {
    require_once 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testHighestEmploymentID = $testModel->getEmploymentWithHighestID();

    $this->assertEquals(6, $testHighestEmploymentID);

    $testModel->deleteEmploymentByEmploymentID(6);

    $testHighestEmploymentID = $testModel->getEmploymentWithHighestID();

    $this->assertEquals(5, $testHighestEmploymentID);
  }

}
