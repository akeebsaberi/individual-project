<?php

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase {

  public function testGetUserByUsernameAndPassword_validUser() {
    require 'model\user.php';
    require 'model\model.php';

    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    //16, admin, test, unittest, unittestpassword, 1, 1, 2000-01-01, 1, 1, 2, 3, 1, 1

    $testUser = $testModel->getUserByUsernameAndPassword('unittest', 'unittestpassword');

    $this->assertEquals(16, $testUser->employeeNumber);
    $this->assertEquals('admin', $testUser->firstName);
    $this->assertEquals('test', $testUser->surname);
    $this->assertEquals('unittest', $testUser->username);
    $this->assertTrue(password_verify('unittestpassword', $testUser->password));
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

  public function testGetUserByUsernameAndPassword_validUserSessionVariables() {
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
  }

  public function testGetUserByUsernameAndPassword_invalidUsername() {
    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('invalidusername', 'unittestpassword');

    //User does not exist, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_invalidPassword() {
    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('unittest', 'invalidpassword');

    //Username or password is incorrect, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_invalidUsernameAndPassword() {
    $testModel = new Model("127.0.0.1", "individual_project_db", "root", "");
    $testModel->connect();

    $testUser = $testModel->getUserByUsernameAndPassword('invalidusername', 'invalidpassword');

    //User does not exist, please try again.
    $this->assertNull($testUser);
  }

  public function testGetUserByUsernameAndPassword_emptyUsernameAndPassword() {
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
}
