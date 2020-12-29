<?php

include_once("model/user.php");
include_once("model/grade.php");
include_once("model/baselocation.php");
include_once("model/businessunit.php");
include_once("model/project.php");
include_once("model/education.php");
include_once("model/usertoskill.php");
include_once("model/employment.php");

class Model {

  //Fields for database connection config
  private $server;
  private $dbname;
  private $username;
  private $password;
  private $pdo;

  private $projectIDToEdit;
  private $educationIDToEdit;
  private $employmentIDToEdit;

  private $projectIDToDelete;
  private $educationIDToDelete;
  private $employmentIDToDelete;

  //Constructor for Model class
  public function __construct($server, $dbname, $username, $password) {
    $this->pdo = null;
    $this->server = $server;
    $this->dbname = $dbname;
    $this->username = $username;
    $this->password = $password;
  }

  //Connect function to create a db connection
  public function connect() {
    try {
      $this->pdo = new PDO("mysql:host=$this->server;dbname=$this->dbname", "$this->username", "$this->password");
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $ex) {
      echo "<p> a database error occurred: <em> <?= $ex->getMessage() ?> </em></p>";
    }
  }

  //Login function to authenticate a user
  public function getUserByUsernameAndPassword($username, $password) {
    //Sanitise inputs
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    //Prepared SQL statement
    $query = "SELECT * FROM users WHERE Username='$username'";
    //Run query to search for user by username
    try {
      $rows = $this->pdo-> query($query);
      //Check to see if user has been found in database
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        //Check to see if correct password has been entered
        if (password_verify($password, $row["Password"])) {
          //Password correct, create user object
          $user=new User($row["EmployeeNumber"], $row["FirstName"], $row["Surname"], $row["Username"], $row["Password"], $row["Email"], $row["IsResourceManager"], $row["IsAdmin"], $row["DateOfBirth"], $row["BaseLocation"], $row["LineManager"], $row["ReviewerManager"], $row["ResourceManager"], $row["BusinessUnit"], $row["Grade"]);
          //Set session variables for authenticated user
          $_SESSION['user']['EmployeeNumber'] = $user->employeeNumber;
          $_SESSION['user']['FirstName'] = $user->firstName;
          $_SESSION['user']['Surname'] = $user->surname;
          $_SESSION['user']['Username'] = $user->username;
          $_SESSION['user']['Password'] = $user->password;
          $_SESSION['user']['Email'] = $user->email;
          $_SESSION['user']['IsResourceManager'] = $user->isResourceManager;
          $_SESSION['user']['IsAdmin'] = $user->isAdmin;
          $_SESSION['user']['DateOfBirth'] = $user->dateOfBirth;
          $_SESSION['user']['BaseLocation'] = $user->baseLocation;
          $_SESSION['user']['LineManager'] = $user->lineManager;
          $_SESSION['user']['ReviewerManager'] = $user->reviewerManager;
          $_SESSION['user']['ResourceManager'] = $user->resourceManager;
          $_SESSION['user']['BusinessUnit'] = $user->businessUnit;
          $_SESSION['user']['Grade'] = $user->grade;
          //Return user object
          return $user;
        }
        else {
          //Incorrect password entered, print out error message
          echo '<div class="container">';
          echo '<p>Username or password is incorrect, please try again.</p>';
          echo '</div>';
          //Return null (no user object)
          return null;
        }
      }
      //Incorrect username/username does not exist, print out error message
      echo '<div class="container">';
      echo '<p>User does not exist, please try again.</p>';
      echo '</div>';
      //Return null (no user object)
      return null;
    }
    //PDO Exception
    catch (PDOException $ex) {
      echo "<p> database error occurred: <em> $ex->getMessage() </em></p>";
    }
  }

  //Function to create a corresponding user object for a given employee number
  public function getUserDetailsByEmployeeNumber($employeeNumber) {
    //Prepared SQL statement
    $query = "SELECT * from users WHERE EmployeeNumber=$employeeNumber";
    try {
      $rows = $this->pdo-> query($query);
      //Check to see if user has been found in database
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        //Create user object from user record obtained from database
        $user=new User($row["EmployeeNumber"], $row["FirstName"], $row["Surname"], $row["Username"], $row["Password"], $row["Email"], $row["IsResourceManager"], $row["IsAdmin"], $row["DateOfBirth"], $row["BaseLocation"], $row["LineManager"], $row["ReviewerManager"], $row["ResourceManager"], $row["BusinessUnit"], $row["Grade"]);
        //Return user object
        return $user;
      }
      //No user found, return null
      else return null;
    }
    //PDO Exception
    catch (PDOException $ex) {
      echo "<p> database error occurred: <em> $ex->getMessage() </em></p>";
    }
  }

  //Construct grade object from grade ID
  public function constructGradeFromGradeID($gradeIDFromSession) {
    $sessionGradeID = $gradeIDFromSession;
    $query = "SELECT * FROM grades WHERE GradeID = '$sessionGradeID'";
    try {
      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $grade = new Grade($row["GradeID"], $row["Grade"], $row["JobTitle"]);
        return $grade;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function constructBaseLocationFromBaseLocationID($baseLocationIDFromSession) {
    $sessionBaseLocationID = $baseLocationIDFromSession;
    $query = "SELECT * FROM baselocations WHERE BaseLocationID = '$sessionBaseLocationID'";
    try {
      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $baseLocation = new BaseLocation($row["BaseLocationID"], $row["Name"], $row["City"], $row["Country"]);
        return $baseLocation;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function constructBusinessUnitFromBusinessUnitID($businessUnitIDFromSession) {
    $sessionBusinessUnitID = $businessUnitIDFromSession;
    $query = "SELECT * FROM businessunits WHERE BusinessUnitID = '$sessionBusinessUnitID'";
    try {
      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $businessUnit = new BusinessUnit($row["BusinessUnitID"], $row["Unit"]);
        return $businessUnit;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  //get all projects associated with the logged in users
  public function getAllProjectsAssociatedWithThisUser($sessionEmployeeNumber) {
    $query = "SELECT * FROM projects WHERE EmployeeNumber = '$sessionEmployeeNumber'";
    try {
      $projectsArray = array();
      $result = $this->pdo-> query($query);
      if ($result && $result->rowCount() > 0) {
        foreach($result as $row) {
          $project = new Project($row["ProjectID"], $row["EmployeeNumber"], $row["ProjectName"], $row["Customer"], $row["ProjectDescription"], $row["FromDate"], $row["ToDate"]);
          array_push($projectsArray, $project);
        }
        return $projectsArray;
      }
      else {
        return 0;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  //get all projects associated with the logged in users
  public function getAllEducationAssociatedWithThisUser($sessionEmployeeNumber) {
    $query = "SELECT * FROM education WHERE EmployeeNumber = '$sessionEmployeeNumber'";
    try {
      $educationArray = array();
      $result = $this->pdo-> query($query);
      if ($result && $result->rowCount() > 0) {
        foreach($result as $row) {
          $education = new Education($row["EducationID"], $row["EmployeeNumber"], $row["Subject"], $row["Level"], $row["FromDate"], $row["ToDate"]);
          array_push($educationArray, $education);
        }
        return $educationArray;
      }
      else {
        return 0;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getAllSkillsAssociatedWithThisUser($sessionEmployeeNumber) {
    $query = "SELECT u.UserToSkillID, u.EmployeeNumber, u.SkillID, s.SkillName, s.SkillType, u.IsCoreSkill, u.CompetencyLevel, u.ExperienceInYears FROM userstoskills u INNER JOIN skills s ON u.SkillID = s.SkillID WHERE u.EmployeeNumber = '$sessionEmployeeNumber'";
    try {
      $userToSkillsArray = array();
      $result = $this->pdo-> query($query);
      if ($result && $result->rowCount() > 0) {
        foreach($result as $row) {
          $userToSkill = new UserToSkill($row["UserToSkillID"], $row["EmployeeNumber"], $row["SkillID"], $row["SkillName"], $row["SkillType"], $row["IsCoreSkill"], $row["CompetencyLevel"] , $row["ExperienceInYears"]);
          array_push($userToSkillsArray, $userToSkill);
        }
        return $userToSkillsArray;
      }
      else {
        return 0;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getAllEmploymentAssociatedWithThisUser($sessionEmployeeNumber) {
    $query = "SELECT * FROM employment WHERE EmployeeNumber = '$sessionEmployeeNumber'";
    try {
      $employmentArray = array();
      $result = $this->pdo-> query($query);
      if ($result && $result->rowCount() > 0) {
        foreach($result as $row) {
          $employment = new Employment($row["EmploymentID"], $row["EmployeeNumber"], $row["Company"], $row["FromDate"] , $row["ToDate"]);
          array_push($employmentArray, $employment);
        }
        return $employmentArray;
      }
      else {
        return 0;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getProjectWithHighestID() {
    $query = "SELECT * FROM projects WHERE ProjectID = (SELECT MAX(ProjectID) FROM projects)";
    try {
      $maxID = 0;

      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $maxID = $row["ProjectID"];
        return $maxID;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getEducationWithHighestID() {
    $query = "SELECT * FROM education WHERE EducationID = (SELECT MAX(EducationID) FROM education)";
    try {
      $maxID = 0;

      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $maxID = $row["EducationID"];
        return $maxID;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getEmploymentWithHighestID() {
    $query = "SELECT * FROM employment WHERE EmploymentID = (SELECT MAX(EmploymentID) FROM employment)";
    try {
      $maxID = 0;

      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $maxID = $row["EmploymentID"];
        return $maxID;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewProjectToDatabase($projectID, $employeeNumber, $projectName, $customer, $projectDescription, $fromDate, $toDate) {
    try {
      $execute = "INSERT INTO projects (ProjectID, EmployeeNumber, ProjectName, Customer, ProjectDescription, FromDate, ToDate) VALUES ('$projectID', '$employeeNumber', '$projectName', '$customer', '$projectDescription', '$fromDate', '$toDate')";
      $this->pdo->exec($execute);
      echo 'Project has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewEducationToDatabase($educationID, $employeeNumber, $subject, $level, $fromDate, $toDate) {
    try {
      $execute = "INSERT INTO education (EducationID, EmployeeNumber, Subject, Level, FromDate, ToDate) VALUES ('$educationID', '$employeeNumber', '$subject', '$level', '$fromDate', '$toDate')";
      $this->pdo->exec($execute);
      echo 'Education record has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewEmploymentToDatabase($employmentID, $employeeNumber, $company, $fromDate, $toDate) {
    try {
      $execute = "INSERT INTO employment (EmploymentID, EmployeeNumber, Company, FromDate, ToDate) VALUES ('$employmentID', '$employeeNumber', '$company', '$fromDate', '$toDate')";
      $this->pdo->exec($execute);
      echo 'Employment record has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editProjectDetails($projectID, $projectName, $customer, $projectDescription, $fromDate, $toDate) {
    try {
      $execute = "UPDATE projects SET ProjectName='$projectName', Customer='$customer', ProjectDescription='$projectDescription', FromDate='$fromDate', ToDate='$toDate' WHERE ProjectID='$projectID'";
      $this->pdo->exec($execute);
      echo "Updated Project Details";
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editEducationDetails($educationID, $subject, $level, $fromDate, $toDate) {
    try {
      $execute = "UPDATE education SET Subject='$subject', Level='$level', FromDate='$fromDate', ToDate='$toDate' WHERE EducationID='$educationID'";
      $this->pdo->exec($execute);
      echo "Updated Education Details";
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editEmploymentDetails($employmentID, $company, $fromDate, $toDate) {
    try {
      $execute = "UPDATE employment SET Company='$company', FromDate='$fromDate', ToDate='$toDate' WHERE EmploymentID='$employmentID'";
      $this->pdo->exec($execute);
      echo "Updated Employment Details";
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getProjectToEdit() {
    return $this->projectIDToEdit;
  }

  public function setProjectToEdit($editProjectByID) {
    $this->projectIDToEdit = $editProjectByID;
  }

  public function getProjectToBeEdited($projectID) {
    $projectIDToBeEdited = $projectID;
    $query = "SELECT * FROM projects WHERE ProjectID = '$projectIDToBeEdited'";
    try {
      $rows = $this->pdo->query($query);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $projectObject = new Project($row["ProjectID"], $row["EmployeeNumber"], $row["ProjectName"], $row["Customer"], $row["ProjectDescription"], $row["FromDate"], $row["ToDate"]);
        return $projectObject;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getEducationToEdit() {
    return $this->educationIDToEdit;
  }

  public function setEducationToEdit($editEducationtByID) {
    $this->educationIDToEdit = $editEducationtByID;
  }

  public function getEducationToBeEdited($educationID) {
    $educationIDToBeEdited = $educationID;
    $query = "SELECT * FROM education WHERE EducationID = '$educationIDToBeEdited'";
    try {
      $rows = $this->pdo->query($query);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $educationObject = new Education($row["EducationID"], $row["EmployeeNumber"], $row["Subject"], $row["Level"], $row["FromDate"], $row["ToDate"]);
        return $educationObject;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getEmploymentToEdit() {
    return $this->employmentIDToEdit;
  }

  public function setEmploymentToEdit($editEmploymentByID) {
    $this->employmentIDToEdit = $editEmploymentByID;
  }

  public function getEmploymentToBeEdited($employmentID) {
    $employmentIDToBeEdited = $employmentID;
    $query = "SELECT * FROM employment WHERE EmploymentID = '$employmentIDToBeEdited'";
    try {
      $rows = $this->pdo->query($query);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $employmentObject = new Employment($row["EmploymentID"], $row["EmployeeNumber"], $row["Company"], $row["FromDate"], $row["ToDate"]);
        return $employmentObject;
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function deleteProjectByProjectID($projectID) {
    try {
      if(is_numeric($projectID)) {
        $projectIDToDelete = $projectID;
        $execute = "DELETE FROM projects WHERE ProjectID = '$projectIDToDelete'";
        $this->pdo->exec($execute);
        echo 'Deleted Project Record';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function deleteEducationByEducationID($educationID) {
    try {
      if(is_numeric($educationID)) {
        $educationIDToDelete = $educationID;
        $execute = "DELETE FROM education WHERE EducationID = '$educationIDToDelete'";
        $this->pdo->exec($execute);
        echo 'Deleted Education Record';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function deleteEmploymentByEmploymentID($employmentID) {
    try {
      if(is_numeric($employmentID)) {
        $employmentIDToDelete = $employmentID;
        $execute = "DELETE FROM employment WHERE EmploymentID = '$employmentIDToDelete'";
        $this->pdo->exec($execute);
        echo 'Deleted Employment Record';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function setProjectToDelete($deleteProjectByID) {
    $this->projectIDToDelete = $deleteProjectByID;
  }

  public function getProjectToDelete() {
    return $this->projectIDToDelete;
  }

  public function setEducationToDelete($deleteEducationByID) {
    $this->educationIDToDelete = $deleteEducationByID;
  }

  public function getEducationToDelete() {
    return $this->educationIDToDelete;
  }

  public function setEmploymentToDelete($deleteEmploymentByID) {
    $this->employmentIDToDelete = $deleteEmploymentByID;
  }

  public function getEmploymentToDelete() {
    return $this->employmentIDToDelete;
  }

  public function getProjectByProjectID($projectID) {
    if (is_numeric($projectID)) {
      $query = "SELECT * FROM projects WHERE ProjectID = $projectID";
      try {
        $rows = $this->pdo->query($query);
        if ($rows && $rows->rowCount() == 1) {
          $row=$rows->fetch();
          $projectObject = new Project($row["ProjectID"], $row["EmployeeNumber"], $row["ProjectName"], $row["Customer"], $row["ProjectDescription"], $row["FromDate"], $row["ToDate"]);
          return $projectObject;
        }
      }
      catch (PDOException $ex) {
        echo  "<p>Sorry, a database error occurred. Please try again.</p>";
        echo $ex->getMessage();
      }
    }
  }

  public function getEducationByEducationID($educationID) {
    if (is_numeric($educationID)) {
      $query = "SELECT * FROM education WHERE EducationID = $educationID";
      try {
        $rows = $this->pdo->query($query);
        if ($rows && $rows->rowCount() == 1) {
          $row=$rows->fetch();
          $educationObject = new Education($row["EducationID"], $row["EmployeeNumber"], $row["Subject"], $row["Level"], $row["FromDate"], $row["ToDate"]);
          return $educationObject;
        }
      }
      catch (PDOException $ex) {
        echo  "<p>Sorry, a database error occurred. Please try again.</p>";
        echo $ex->getMessage();
      }
    }
  }

  public function getEmploymentByEmploymentID($employmentID) {
    if (is_numeric($employmentID)) {
      $query = "SELECT * FROM employment WHERE EmploymentID = $employmentID";
      try {
        $rows = $this->pdo->query($query);
        if ($rows && $rows->rowCount() == 1) {
          $row=$rows->fetch();
          $employmentObject = new Employment($row["EmploymentID"], $row["EmployeeNumber"], $row["Company"], $row["FromDate"], $row["ToDate"]);
          return $employmentObject;
        }
      }
      catch (PDOException $ex) {
        echo  "<p>Sorry, a database error occurred. Please try again.</p>";
        echo $ex->getMessage();
      }
    }
  }

}

?>
