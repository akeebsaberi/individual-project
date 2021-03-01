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
  private $userToSkillIDToEdit;
  private $employmentIDToEdit;

  private $projectIDToDelete;
  private $educationIDToDelete;
  private $userToSkillIDToDelete;
  private $employmentIDToDelete;

  private $employeeIDToView;

  private $IDForGeneratePDF;
  private $IDForGenerateExternalPDF;

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
    //Run query to search for user by username
    try {
      $rows = $this->pdo->prepare("SELECT * FROM users WHERE Username = ?");
      $rows->execute([$username]);
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
    try {
      $rows = $this->pdo->prepare("SELECT * from users WHERE EmployeeNumber = ?");
      $rows->execute([$employeeNumber]);
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
    $query = "SELECT * FROM grades WHERE GradeID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$sessionGradeID]);
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
    $query = "SELECT * FROM baselocations WHERE BaseLocationID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$sessionBaseLocationID]);
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
    $query = "SELECT * FROM businessunits WHERE BusinessUnitID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$sessionBusinessUnitID]);
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
    $query = "SELECT * FROM projects WHERE EmployeeNumber = ?";
    try {
      $projectsArray = array();
      $result = $this->pdo->prepare($query);
      $result->execute([$sessionEmployeeNumber]);
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
    $query = "SELECT * FROM education WHERE EmployeeNumber = ?";
    try {
      $educationArray = array();
      $result = $this->pdo->prepare($query);
      $result->execute([$sessionEmployeeNumber]);
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
    $query = "SELECT u.UserToSkillID, u.EmployeeNumber, u.SkillID, s.SkillName, s.SkillType, u.IsCoreSkill, u.CompetencyLevel, u.ExperienceInYears FROM userstoskills u INNER JOIN skills s ON u.SkillID = s.SkillID WHERE u.EmployeeNumber = ?";
    try {
      $userToSkillsArray = array();
      $result = $this->pdo->prepare($query);
      $result->execute([$sessionEmployeeNumber]);
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
    $query = "SELECT * FROM employment WHERE EmployeeNumber = ?";
    try {
      $employmentArray = array();
      $result = $this->pdo->prepare($query);
      $result->execute([$sessionEmployeeNumber]);
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

  public function getUserToSkillWithHighestID() {
    $query = "SELECT * FROM userstoskills WHERE UserToSkillID = (SELECT MAX(UserToSkillID) FROM userstoskills)";
    try {
      $maxID = 0;

      $rows = $this->pdo-> query($query);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $maxID = $row["UserToSkillID"];
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
      $stmt = "INSERT INTO projects (ProjectID, EmployeeNumber, ProjectName, Customer, ProjectDescription, FromDate, ToDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$projectID, $employeeNumber, $projectName, $customer, $projectDescription, $fromDate, $toDate]);
      echo 'Project has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewEducationToDatabase($educationID, $employeeNumber, $subject, $level, $fromDate, $toDate) {
    try {
      $stmt = "INSERT INTO education (EducationID, EmployeeNumber, Subject, Level, FromDate, ToDate) VALUES (?, ?, ?, ?, ?, ?)";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$educationID, $employeeNumber, $subject, $level, $fromDate, $toDate]);
      echo 'Education record has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewUserToSkillToDatabase($userToSkillID, $employeeNumber, $skillName, $isCoreSkill, $competencyLevel, $experienceInYears) {
    $query = "SELECT * FROM skills WHERE SkillName = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$skillName]);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $skillIDFromDatabase = $row["SkillID"];

        $stmt = "INSERT INTO userstoskills (UserToSkillID, EmployeeNumber, SkillID, IsCoreSkill, CompetencyLevel, ExperienceInYears) VALUES (?, ?, ?, ?, ?, ?)";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$userToSkillID, $employeeNumber, $skillIDFromDatabase, $isCoreSkill, $competencyLevel, $experienceInYears]);
        echo 'Skill record has been added.';
      }
      else {
        echo '<div class="container">';
        echo '<p>Sorry, an error has occurred. Please ensure you have selected the skill from the dropdown</p>';
        echo '</div>';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function addNewEmploymentToDatabase($employmentID, $employeeNumber, $company, $fromDate, $toDate) {
    try {
      $stmt = "INSERT INTO employment (EmploymentID, EmployeeNumber, Company, FromDate, ToDate) VALUES (?, ?, ?, ?, ?)";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$employmentID, $employeeNumber, $company, $fromDate, $toDate]);
      echo 'Employment record has been added.';
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editProjectDetails($projectID, $projectName, $customer, $projectDescription, $fromDate, $toDate) {
    try {
      $stmt = "UPDATE projects SET ProjectName=?, Customer=?, ProjectDescription=?, FromDate=?, ToDate=? WHERE ProjectID=?";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$projectName, $customer, $projectDescription, $fromDate, $toDate, $projectID]);
      echo "Updated Project Details";
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editEducationDetails($educationID, $subject, $level, $fromDate, $toDate) {
    try {
      $stmt = "UPDATE education SET Subject=?, Level=?, FromDate=?, ToDate=? WHERE EducationID=?";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$subject, $level, $fromDate, $toDate, $educationID]);
      echo "Updated Education Details";
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editUserToSkillDetails($userToSkillID, $employeeNumber, $skillName, $isCoreSkill, $competencyLevel, $experienceInYears) {
    $query = "SELECT * FROM skills WHERE SkillName = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$skillName]);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $skillIDFromDatabase = $row["SkillID"];

        $stmt = "UPDATE userstoskills SET SkillID=?, IsCoreSkill=?, CompetencyLevel=?, ExperienceInYears=? WHERE UserToSkillID=?";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$skillIDFromDatabase, $isCoreSkill, $competencyLevel, $experienceInYears, $userToSkillID]);
        echo "Updated Skill Details";
      }
      else {
        echo '<div class="container">';
        echo '<p>Sorry, an error has occurred. Please ensure you have selected the skill from the dropdown</p>';
        echo '</div>';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function editEmploymentDetails($employmentID, $company, $fromDate, $toDate) {
    try {
      $stmt = "UPDATE employment SET Company=?, FromDate=?, ToDate=? WHERE EmploymentID=?";
      $result = $this->pdo->prepare($stmt);
      $result->execute([$company, $fromDate, $toDate, $employmentID]);
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
    $query = "SELECT * FROM projects WHERE ProjectID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$projectIDToBeEdited]);
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
    $query = "SELECT * FROM education WHERE EducationID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$educationIDToBeEdited]);
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

  public function setUserToSkillToEdit($editUserToSkillByID) {
    $this->userToSkillIDToEdit = $editUserToSkillByID;
  }

  public function getUserToSkillToEdit() {
    return $this->userToSkillIDToEdit;
  }

  public function getUserToSkillToBeEdited($userToSkillID) {
    $userToSkillIDToBeEdited = $userToSkillID;
    $query = "SELECT u.UserToSkillID, u.EmployeeNumber, u.SkillID, s.SkillName, s.SkillType, u.IsCoreSkill, u.CompetencyLevel, u.ExperienceInYears FROM userstoskills u INNER JOIN skills s ON u.SkillID = s.SkillID WHERE u.UserToSkillID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$userToSkillIDToBeEdited]);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        $userToSkillObject = new UserToSkill($row["UserToSkillID"], $row["EmployeeNumber"], $row["SkillID"], $row["SkillName"], $row["SkillType"], $row["IsCoreSkill"], $row["CompetencyLevel"] , $row["ExperienceInYears"]);
        return $userToSkillObject;
      }
      else {
        return null;
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
    $query = "SELECT * FROM employment WHERE EmploymentID = ?";
    try {
      $rows = $this->pdo->prepare($query);
      $rows->execute([$employmentIDToBeEdited]);
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
        $stmt = "DELETE FROM projects WHERE ProjectID = ?";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$projectID]);
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
        $stmt = "DELETE FROM education WHERE EducationID = ?";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$educationID]);
        echo 'Deleted Education Record';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function deleteUserToSkillByUserToSkillID($userToSkillID) {
    try {
      if(is_numeric($userToSkillID)) {
        $userToSkillIDToDelete = $userToSkillID;
        $stmt = "DELETE FROM userstoskills WHERE UserToSkillID = ?";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$userToSkillID]);
        echo 'Deleted Skill Record';
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
        $stmt = "DELETE FROM employment WHERE EmploymentID = ?";
        $result = $this->pdo->prepare($stmt);
        $result->execute([$employmentIDToDelete]);
        echo 'Deleted Employment Record';
      }
    }
    catch (PDOException $ex) {
      echo  "<p>Sorry, a database error occurred. Please try again.</p>";
      echo $ex->getMessage();
    }
  }

  public function getUserToSkillArrayByAdvancedSkillSearch($skillName, $minimumExperienceInYears, $competencyLevel) {
    $query = "SELECT u.UserToSkillID, u.EmployeeNumber, u.SkillID, s.SkillName, s.SkillType, u.IsCoreSkill, u.CompetencyLevel, u.ExperienceInYears FROM userstoskills u INNER JOIN skills s ON u.SkillID = s.SkillID WHERE s.SkillName = ? AND CompetencyLevel = ? AND ExperienceInYears >= ?";
    try {
      $usersToSkillsArray = array();
      $rows = $this->pdo->prepare($query);
      $rows->execute([$skillName, $minimumExperienceInYears, $competencyLevel]);
      if ($rows && $rows->rowCount() > 0) {
        foreach ($rows as $row) {
          $usertoskill = new UserToSkill($row["UserToSkillID"], $row["EmployeeNumber"], $row["SkillID"], $row["SkillName"], $row["SkillType"], $row["IsCoreSkill"], $row["CompetencyLevel"] , $row["ExperienceInYears"]);
          array_push($usersToSkillsArray, $usertoskill);
        }
          return $usersToSkillsArray;
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

  public function setUserToSkillToDelete($deleteUserToSkillByID) {
    $this->userToSkillIDToDelete = $deleteUserToSkillByID;
  }

  public function getUserToSkillToDelete() {
    return $this->userToSkillIDToDelete;
  }

  public function setEmploymentToDelete($deleteEmploymentByID) {
    $this->employmentIDToDelete = $deleteEmploymentByID;
  }

  public function getEmploymentToDelete() {
    return $this->employmentIDToDelete;
  }

  public function getProjectByProjectID($projectID) {
    if (is_numeric($projectID)) {
      $query = "SELECT * FROM projects WHERE ProjectID = ?";
      try {
        $rows = $this->pdo->prepare($query);
        $rows->execute([$projectID]);
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
      $query = "SELECT * FROM education WHERE EducationID = ?";
      try {
        $rows = $this->pdo->prepare($query);
        $rows->execute([$educationID]);
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

  public function getUserToSkillByUserToSkillID($userToSkillID) {
    if (is_numeric($userToSkillID)) {
      $query = "SELECT u.UserToSkillID, u.EmployeeNumber, u.SkillID, s.SkillName, s.SkillType, u.IsCoreSkill, u.CompetencyLevel, u.ExperienceInYears FROM userstoskills u INNER JOIN skills s ON u.SkillID = s.SkillID WHERE u.UserToSkillID = ?";
      try {
        $rows = $this->pdo->prepare($query);
        $rows->execute([$userToSkillID]);
        if ($rows && $rows->rowCount() == 1) {
          $row=$rows->fetch();
          $userToSkillObject = new UserToSkill($row["UserToSkillID"], $row["EmployeeNumber"], $row["SkillID"], $row["SkillName"], $row["SkillType"], $row["IsCoreSkill"], $row["CompetencyLevel"] , $row["ExperienceInYears"]);
          return $userToSkillObject;
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
      $query = "SELECT * FROM employment WHERE EmploymentID = ?";
      try {
        $rows = $this->pdo->prepare($query);
        $rows->execute([$employmentID]);
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

  public function setEmployeeIDToView($employeeID) {
    $this->employeeIDToView = $employeeID;
  }

  public function getEmployeeIDToView() {
    return $this->employeeIDToView;
  }

  public function setIDForGeneratePDF($employeeID) {
    $this->IDForGeneratePDF = $employeeID;
  }

  public function getIDForGeneratePDF() {
    return $this->IDForGeneratePDF;
  }

  public function setIDForGenerateExternalPDF($employeeID) {
    $this->IDForGenerateExternalPDF = $employeeID;
  }

  public function getIDForGenerateExternalPDF() {
    return $this->IDForGenerateExternalPDF;
  }

  public function processExternalPDFOptions($externalPDFOptions) {

    if ($externalPDFOptions == 0) {
      $checkboxBaseLocation = 0;
      $checkboxBusinessUnit = 0;
      $checkboxGrade = 0;
      $checkboxLineManager = 0;
      $checkboxResourceManager = 0;
      $checkboxReviewerManager = 0;
      $checkboxProjects = 0;
      $checkboxEducation = 0;
      $checkboxSkills = 0;
      $checkboxEmployment = 0;
    }
    else {
      if (in_array("baselocation", $externalPDFOptions)) {
        //$this->checkboxBaseLocation = 1;
        $_SESSION['checkbox_base_location'] = 1;
      }
      else if (!in_array("baselocation", $externalPDFOptions)){
        //$this->checkboxBaseLocation = 0;
        $_SESSION['checkbox_base_location'] = 0;
      }
      if (in_array("businessunit", $externalPDFOptions)) {
        //$this->checkboxBusinessUnit = 1;
        $_SESSION['checkbox_business_unit'] = 1;
      }
      else {
        //$this->checkboxBusinessUnit = 0;
        $_SESSION['checkbox_business_unit'] = 0;
      }
      if (in_array("grade", $externalPDFOptions)) {
        //$this->checkboxGrade = 1;
        $_SESSION['checkbox_grade'] = 1;
      }
      else {
        //$this->checkboxGrade = 0;
        $_SESSION['checkbox_grade'] = 0;
      }
      if (in_array("linemanager", $externalPDFOptions)) {
        //$this->checkboxLineManager = 1;
        $_SESSION['checkbox_line_manager'] = 1;
      }
      else {
        //$this->checkboxLineManager = 0;
        $_SESSION['checkbox_line_manager'] = 0;
      }
      if (in_array("resourcemanager", $externalPDFOptions)) {
        //$this->checkboxResourceManager = 1;
        $_SESSION['checkbox_resource_manager'] = 1;
      }
      else {
        //$this->checkboxResourceManager = 0;
        $_SESSION['checkbox_resource_manager'] = 0;
      }
      if (in_array("reviewermanager", $externalPDFOptions)) {
        //$this->checkboxReviewerManager = 1;
        $_SESSION['checkbox_reviewer_manager'] = 1;
      }
      else {
        //$this->checkboxReviewerManager = 0;
        $_SESSION['checkbox_reviewer_manager'] = 0;
      }
      if (in_array("project", $externalPDFOptions)) {
        //$this->checkboxProjects = 1;
        $_SESSION['checkbox_project'] = 1;
      }
      else {
        //$this->checkboxProjects = 0;
        $_SESSION['checkbox_project'] = 0;
      }
      if (in_array("education", $externalPDFOptions)) {
        //$this->checkboxEducation = 1;
        $_SESSION['checkbox_education'] = 1;
      }
      else {
        //$this->checkboxEducation = 0;
        $_SESSION['checkbox_education'] = 0;
      }
      if (in_array("skills", $externalPDFOptions)) {
        //$this->checkboxSkills = 1;
        $_SESSION['checkbox_skills'] = 1;
      }
      else {
        //$this->checkboxSkills = 0;
        $_SESSION['checkbox_skills'] = 0;
      }
      if (in_array("employment", $externalPDFOptions)) {
        //$this->checkboxEmployment = 1;
        $_SESSION['checkbox_employment'] = 1;
      }
      else {
        //$this->checkboxEmployment = 0;
        $_SESSION['checkbox_employment'] = 0;
      }
    }
  }

}

?>
