<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

include_once("model/user.php");
include_once("model/grade.php");
include_once("model/baselocation.php");
include_once("model/businessunit.php");
include_once("model/project.php");
include_once("model/education.php");
include_once("model/usertoskill.php");
include_once("model/employment.php");

/**
*
* Model class containing all business logic as functions
*
* The Model class contains all functions used by the application. Many of the functions inteeract with the MySQL Database
* and handles model instantiation for Views.
*
*/
class Model {

  /**
  * Class fields to store database connection strings - includes server name, database name, username, password and PDO object
  */
  private $server;
  private $dbname;
  private $username;
  private $password;
  private $pdo;

  /**
  * Class fields to store the IDs of project, education, usertoskill and employment records being targeted for editing (Manage CV)
  */
  private $projectIDToEdit;
  private $educationIDToEdit;
  private $userToSkillIDToEdit;
  private $employmentIDToEdit;

  /**
  * Class fields to store the IDs of project, education, usertoskill and employment records being targeted for deletion (Manage CV)
  */
  private $projectIDToDelete;
  private $educationIDToDelete;
  private $userToSkillIDToDelete;
  private $employmentIDToDelete;

  /**
  * Class field to store the Employee Number of the targeted user (Search By Employee - view user from search)
  */
  private $employeeIDToView;

  /**
  * Class fields to store the Employee Number of the target user for CV exporting to PDF (Export to Internal/External CV)
  */
  private $IDForGeneratePDF;
  private $IDForGenerateExternalPDF;

  /**
  * Class constructor
  * @param    $server     Name of the server that the database is hosted on
  * @param    $dbname     Name of the database being connected to
  * @param    $username   Username to access the database
  * @param    $password   Password to access the database
  */
  public function __construct($server, $dbname, $username, $password) {
    $this->pdo = null;
    $this->server = $server;
    $this->dbname = $dbname;
    $this->username = $username;
    $this->password = $password;
  }

  /**
  * Connect function to initiate the connection to the database as a PDO object.
  * PDO object stored in PDO field in Model class.
  *
  * @access   public
  */
  public function connect() {
    try {
      $this->pdo = new PDO("mysql:host=$this->server;dbname=$this->dbname", "$this->username", "$this->password");
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $ex) {
      echo "<p> a database error occurred: <em> <?= $ex->getMessage() ?> </em></p>";
    }
  }

  /**
  * Login function to authenticate users that enter a valid username and password combination
  * The database is queried to establish whether the username entered returns a user and establishes whether the provided password matches that of the database
  * On successful authentication, session variables are set to store the authenticated user's details
  * If username or password is incorrect, an error message is output
  *
  * @param    $username   Username entered by the user in the login form
  * @param    $password   Password entered by the user in the login form
  * @return   $user       User object containing user details is returned on successful authentication (user object instantiated and populated with details from the users table in the database)
  * @access   public
  */
  public function getUserByUsernameAndPassword($username, $password) {
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);
    try {
      $rows = $this->pdo->prepare("SELECT * FROM users WHERE Username = ?");
      $rows->execute([$username]);
      if ($rows && $rows->rowCount() == 1) {
        $row=$rows->fetch();
        if (password_verify($password, $row["Password"])) {
          $user=new User($row["EmployeeNumber"], $row["FirstName"], $row["Surname"], $row["Username"], $row["Password"], $row["Email"], $row["IsResourceManager"], $row["IsAdmin"], $row["DateOfBirth"], $row["BaseLocation"], $row["LineManager"], $row["ReviewerManager"], $row["ResourceManager"], $row["BusinessUnit"], $row["Grade"]);
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
          return $user;
        }
        else {
          echo '<div class="container">';
          echo '<p>Username or password is incorrect, please try again.</p>';
          echo '</div>';
          return null;
        }
      }
      echo '<div class="container">';
      echo '<p>User does not exist, please try again.</p>';
      echo '</div>';
      return null;
    }
    catch (PDOException $ex) {
      echo "<p> database error occurred: <em> $ex->getMessage() </em></p>";
    }
  }

  /**
  * Function to obtain a User object for a user given thier Employee Number
  * Queries users table in the database to obtain user information for the employee with the given Employee Number
  *
  * @param    $employeeNumber   Username entered by the user in the login form
  * @return   $user             User object containing user details is returned if the provided Employee Number is valid
  * @access   public
  */
  public function getUserDetailsByEmployeeNumber($employeeNumber) {
    try {
      $rows = $this->pdo->prepare("SELECT * from users WHERE EmployeeNumber = ?");
      $rows->execute([$employeeNumber]);
      if ($rows && $rows->rowCount() ==1) {
        $row=$rows->fetch();
        $user=new User($row["EmployeeNumber"], $row["FirstName"], $row["Surname"], $row["Username"], $row["Password"], $row["Email"], $row["IsResourceManager"], $row["IsAdmin"], $row["DateOfBirth"], $row["BaseLocation"], $row["LineManager"], $row["ReviewerManager"], $row["ResourceManager"], $row["BusinessUnit"], $row["Grade"]);
        return $user;
      }
      else return null;
    }
    catch (PDOException $ex) {
      echo "<p> database error occurred: <em> $ex->getMessage() </em></p>";
    }
  }

  /**
  * Function to construct a Grade object given a valid GradeID
  * Used to obtain meaningful grade information based on the ID provided from the grades table in the database
  *
  * @param    $gradeIDFromSession   GradeID obtained from the GradeID session variable for the authenticated user
  * @return   $grade                Grade object containing grade details is returned if the provided GradeID is valid
  * @access   public
  */
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

  /**
  * Function to construct a BaseLocation object given a valid GradeID
  * Used to obtain meaningful base location information based on the ID provided from the baselocations table in the database
  *
  * @param    $baseLocationIDFromSession    BaseLocationID obtained from the BaseLocationID session variable for the authenticated user
  * @return   $baseLocation                 BaseLocation object containing base location details is returned if the provided BaseLocationID is valid
  * @access   public
  */
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

  /**
  * Function to construct a BusinessUnit object given a valid GradeID
  * Used to obtain meaningful business unit information based on the ID provided from the businessunits table in the database
  *
  * @param    $businessUnitIDFromSession    BusinessUnitID obtained from the BusinessUnitID session variable for the authenticated user
  * @return   $businessUnit                 BusinessUnit object containing business unit details is returned if the provided BusinessUnit is valid
  * @access   public
  */
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

  /**
  * Function to get all project records associated to the user, based on the Employee Number provided
  * Queries the projects table in the database for all project records that belong to the user with the given Employee Number
  *
  * @param    $sessionEmployeeNumber    Employee Number to obtain all project records that belong to this user
  * @return   $projectsArray            Array of Project objects, with each Project object being instantiated and populated with respective project records from the projects table in the database
  * @access   public
  */
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

  /**
  * Function to get all education records associated to the user, based on the Employee Number provided
  * Queries the education table in the database for all education records that belong to the user with the given Employee Number
  *
  * @param    $sessionEmployeeNumber    Employee Number to obtain all education records that belong to this user
  * @return   $educationArray           Array of Education objects, with each Education object being instantiated and populated with respective education records from the education table in the database
  * @access   public
  */
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

  /**
  * Function to get all skill records associated to the user, based on the Employee Number provided
  * Queries the skills and userstoskills tables (via an inner join) in the database for all skill records that belong to the user with the given Employee Number
  *
  * @param    $sessionEmployeeNumber    Employee Number to obtain all skill records that belong to this user
  * @return   $userToSkillsArray        Array of UserToSkill objects, with each UserToSkill object being instantiated and populated with respective skill records from the skills and userstoskills tables in the database
  * @access   public
  */
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

  /**
  * Function to get all employment records associated to the user, based on the Employee Number provided
  * Queries the employment table in the database for all employment records that belong to the user with the given Employee Number
  *
  * @param    $sessionEmployeeNumber    Employee Number to obtain all employment records that belong to this user
  * @return   $employmentArray          Array of Employment objects, with each Employment object being instantiated and populated with respective employment records from the employment table in the database
  * @access   public
  */
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

  /**
  * Function to obtain the highest ProjectID in the projects table in the database
  * Used to ensure that any new projects being added do not break primary key constraints
  *
  * @return   $maxID    The highest ProjectID that exists within the projects table in the database
  * @access   public
  */
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

  /**
  * Function to obtain the highest EducationID in the education table in the database
  * Used to ensure that any new education records being added do not break primary key constraints
  *
  * @return   $maxID    The highest EducationID that exists within the education table in the database
  * @access   public
  */
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

  /**
  * Function to obtain the highest UserToSkillID in the userstoskills table in the database
  * Used to ensure that any new skill records being added do not break primary key constraints
  *
  * @return   $maxID    The highest UserToSkillID that exists within the userstoskills table in the database
  * @access   public
  */
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

  /**
  * Function to obtain the highest EmploymentID in the employment table in the database
  * Used to ensure that any new employment records being added do not break primary key constraints
  *
  * @return   $maxID    The highest EmploymentID that exists within the employment table in the database
  * @access   public
  */
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

  /**
  * Function to add a project record to the projects table in the database
  *
  * @param    $projectID            The ProjectID that this project record will have in the projects table in the database
  * @param    $employeeNumber       The Employee Number of the authenticated user
  * @param    $projectName          The name of the project
  * @param    $customer             The name of the customer for the project
  * @param    $projectDescription   The description of the project (usually long text)
  * @param    $fromDate             The date the employee started this project
  * @param    $toDate               The date the employee finished/left this project
  * @access   public
  */
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

  /**
  * Function to add an education record to the education table in the database
  *
  * @param    $educationID      The EducationID that this education record will have in the education table in the database
  * @param    $employeeNumber   The Employee Number of the authenticated user
  * @param    $subject          The subject name of this education
  * @param    $level            The level of this education
  * @param    $fromDate         The date the employee started this education
  * @param    $toDate           The date the employee finished/left this education
  * @access   public
  */
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

  /**
  * Function to add a usertoskill record to the userstoskills table in the database
  *
  * @param    $userToSkillID        The UserToSkillID that this usertoskill record will have in the userstoskills table in the database
  * @param    $employeeNumber       The Employee Number of the authenticated user
  * @param    $skillName            The name of the skill
  * @param    $isCoreSkill          Whether this skill is considered a core skill or a non-core skill
  * @param    $competencyLevel      The competency level of the user for this skill (rating between 1 and 5)
  * @param    $experienceInYears    The number of years of experience for this skill
  * @access   public
  */
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

  /**
  * Function to add an employment record to the employment table in the database
  *
  * @param    $employmentID     The EmploymentID that this employment record will have in the employment table in the database
  * @param    $employeeNumber   The Employee Number of the authenticated user
  * @param    $company          The name of the company
  * @param    $fromDate         The date the employee started this employment
  * @param    $toDate           The date the employee finished/left this employment
  * @access   public
  */
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

  /**
  * Function to edit a specific project record in the projects table in the database
  *
  * @param    $projectID            The ProjectID of the project record being edited
  * @param    $projectName          The new name of the project
  * @param    $customer             The new name of the customer for the project
  * @param    $projectDescription   The new description of the project (usually long text)
  * @param    $fromDate             The new date the employee started this project
  * @param    $toDate               The new date the employee finished/left this project
  * @access   public
  */
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

  /**
  * Function to edit a specific education record in the education table in the database
  *
  * @param    $educationID      The EducationID of education record being edited
  * @param    $subject          The new subject name of this education
  * @param    $level            The new level of this education
  * @param    $fromDate         The new date the employee started this education
  * @param    $toDate           The new date the employee finished/left this education
  * @access   public
  */
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

  /**
  * Function to edit a specific usertoskill record in the userstoskills table in the database
  *
  * @param    $userToSkillID        The UserToSkillID of the usertoskill record being edited
  * @param    $skillName            The new name of the skill
  * @param    $isCoreSkill          Whether this skill is considered a core skill or a non-core skill
  * @param    $competencyLevel      The new competency level of the user for this skill (rating between 1 and 5)
  * @param    $experienceInYears    The new number of years of experience for this skill
  * @access   public
  */
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

  /**
  * Function to edit a specific employment record in the employment table in the database
  *
  * @param    $employmentID     The EmploymentID of the employment record being edited
  * @param    $company          The new name of the company
  * @param    $fromDate         The new date the employee started this employment
  * @param    $toDate           The new date the employee finished/left this employment
  * @access   public
  */
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

  /**
  * Accessor function to return $projectIDToEdit field - used to get the targeted ProjectID of the project to be edited
  *
  * @return   $getProjectToEdit   The field holding the ProjectID of the project to be edited
  * @access   public
  */
  public function getProjectToEdit() {
    return $this->projectIDToEdit;
  }

  /**
  * Mutator function to set $projectIDToEdit field - used to set the ProjectID of the project record to be edited
  *
  * @param    $editProjectByID    The ProjectID of the project record to be edited
  * @access   public
  */
  public function setProjectToEdit($editProjectByID) {
    $this->projectIDToEdit = $editProjectByID;
  }

  /**
  * Function to obtain the project details of the project record to be edited - details obtained from the database and stored in a Project object
  *
  * @param    $projectID        The ProjectID of the project record to be edited
  * @return   $projectObject    The Project object containing details of the project record to be obtained prior to the edit
  * @access   public
  */
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

  /**
  * Accessor function to return $educationIDToEdit field - used to get the targeted EducationID of the education record to be edited
  *
  * @return   $educationIDToEdit   The field holding the EducationID of the education record to be edited
  * @access   public
  */
  public function getEducationToEdit() {
    return $this->educationIDToEdit;
  }

  /**
  * Mutator function to set $educationIDToEdit field - used to set the EducationID of the education record to be edited
  *
  * @param    $editEducationtByID    The EducationID of the education record to be edited
  * @access   public
  */
  public function setEducationToEdit($editEducationtByID) {
    $this->educationIDToEdit = $editEducationtByID;
  }

  /**
  * Function to obtain the education details of the education record to be edited - details obtained from the database and stored in an Education object
  *
  * @param    $educationID        The EducationID of the education record to be edited
  * @return   $educationObject    The Education object containing details of the education record to be obtained prior to the edit
  * @access   public
  */
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

  /**
  * Accessor function to return $userToSkillIDToEdit field - used to get the targeted UserToSkillID of the usertoskill record to be edited
  *
  * @return   $userToSkillIDToEdit   The field holding the UserToSkillID of the usertoskill record to be edited
  * @access   public
  */
  public function getUserToSkillToEdit() {
    return $this->userToSkillIDToEdit;
  }

  /**
  * Mutator function to set $userToSkillIDToEdit field - used to set the UserToSkillID of the usertoskill record to be edited
  *
  * @param    $editUserToSkillByID    The UserToSkillID of the usertoskill record to be edited
  * @access   public
  */
  public function setUserToSkillToEdit($editUserToSkillByID) {
    $this->userToSkillIDToEdit = $editUserToSkillByID;
  }

  /**
  * Function to obtain the usertoskill details of the usertoskill record to be edited - details obtained from the database and stored in a UserToSkill object
  *
  * @param    $userToSkillID        The UserToSkillID of the usertoskill record to be edited
  * @return   $userToSkillObject    The UserToSkill object containing details of the usertoskill record to be obtained prior to the edit
  * @access   public
  */
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

  /**
  * Accessor function to return $employmentIDToEdit field - used to get the targeted EmploymentID of the employment record to be edited
  *
  * @return   $employmentIDToEdit   The field holding the EmploymentID of the employment record to be edited
  * @access   public
  */
  public function getEmploymentToEdit() {
    return $this->employmentIDToEdit;
  }

  /**
  * Mutator function to set $employmentIDToEdit field - used to set the EmploymentID of the employment record to be edited
  *
  * @param    $editEmploymentByID   The EmploymentID of the employment record to be edited
  * @access   public
  */
  public function setEmploymentToEdit($editEmploymentByID) {
    $this->employmentIDToEdit = $editEmploymentByID;
  }

  /**
  * Function to obtain the employment details of the employment record to be edited - details obtained from the database and stored in an Employment object
  *
  * @param    $employmentID        The EmploymentID of the employment record to be edited
  * @return   $employmentObject    The Employment object containing details of the employment record to be obtained prior to the edit
  * @access   public
  */
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

  /**
  * Function to delete a project given the ProjectID
  *
  * @param    $projectID    The ProjectID of the project record to be deleted
  * @access   public
  */
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

  /**
  * Function to delete an education record given the EducationID
  *
  * @param    $educationID    The EducationID of the education record to be deleted
  * @access   public
  */
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

  /**
  * Function to delete a usertoskill record given the UserToSkillID
  *
  * @param    $userToSkillID    The UserToSkillID of the usertoskill record to be deleted
  * @access   public
  */
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

  /**
  * Function to delete an employment record given the EmploymentID
  *
  * @param    $employmentID    The EmploymentID of the employment record to be deleted
  * @access   public
  */
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

  /**
  * Function to obtain an array of UserToSkill objects that satsisfy the skill search parameters
  *
  * @param    $skillName                  The name of the skill being searched for
  * @param    $minimumExperienceInYears   The minimum experience of years that is searched for
  * @param    $competencyLevel            The competency level being searched for
  * @return   $usersToSkillsArray         The array of UserToSkill objects that satisfy the requirements set in the skill search
  * @access   public
  */
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

  /**
  * Mutator function to set $projectIDToDelete field - used to set the ProjectID of the project record to be deleted
  *
  * @param    $deleteProjectByID   The ProjectID of the project record to be deleted
  * @access   public
  */
  public function setProjectToDelete($deleteProjectByID) {
    $this->projectIDToDelete = $deleteProjectByID;
  }

  /**
  * Accessor function to return $projectIDToDelete field - used to get the targeted ProjectID of the project record to be deleted
  *
  * @return   $projectIDToDelete   The field holding the ProjectID of the project record to be deleted
  * @access   public
  */
  public function getProjectToDelete() {
    return $this->projectIDToDelete;
  }

  /**
  * Mutator function to set $educationIDToDelete field - used to set the EducationID of the education record to be deleted
  *
  * @param    $deleteEducationByID   The EducationID of the education record to be deleted
  * @access   public
  */
  public function setEducationToDelete($deleteEducationByID) {
    $this->educationIDToDelete = $deleteEducationByID;
  }

  /**
  * Accessor function to return $educationIDToDelete field - used to get the targeted EducationID of the education record to be deleted
  *
  * @return   $educationIDToDelete   The field holding the EducationID of the education record to be deleted
  * @access   public
  */
  public function getEducationToDelete() {
    return $this->educationIDToDelete;
  }

  /**
  * Mutator function to set $userToSkillIDToDelete field - used to set the UserToSkillID of the usertoskill record to be deleted
  *
  * @param    $userToSkillIDToDelete   The UserToSkillID of the usertoskill record to be deleted
  * @access   public
  */
  public function setUserToSkillToDelete($deleteUserToSkillByID) {
    $this->userToSkillIDToDelete = $deleteUserToSkillByID;
  }

  /**
  * Accessor function to return $userToSkillIDToDelete field - used to get the targeted UserToSkillID of the usertoskill record to be deleted
  *
  * @return   $userToSkillIDToDelete   The field holding the UserToSkillID of the usertoskill record to be deleted
  * @access   public
  */
  public function getUserToSkillToDelete() {
    return $this->userToSkillIDToDelete;
  }

  /**
  * Mutator function to set $employmentIDToDelete field - used to set the EmploymentID of the employment record to be deleted
  *
  * @param    $deleteEmploymentByID   The EmploymentID of the employment record to be deleted
  * @access   public
  */
  public function setEmploymentToDelete($deleteEmploymentByID) {
    $this->employmentIDToDelete = $deleteEmploymentByID;
  }

  /**
  * Accessor function to return $employmentIDToDelete field - used to get the targeted EmploymentID of the employment record to be deleted
  *
  * @return   $employmentIDToEdit   The field holding the EmploymentID of the employment record to be deleted
  * @access   public
  */
  public function getEmploymentToDelete() {
    return $this->employmentIDToDelete;
  }

  /**
  * Function to obtain a Project object containing details of a project record based on the provided ProjectID
  *
  * @param    $projectID        The ProjectID of the project object required
  * @return   $projectObject    The instantiated project object containing the details of the project with the given projectID
  * @access   public
  */
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

  /**
  * Function to obtain an Education object containing details of an education record based on the provided EducationID
  *
  * @param    $educationID        The EducationID of the education object required
  * @return   $educationObject    The instantiated education object containing the details of the education record with the given EducationID
  * @access   public
  */
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

  /**
  * Function to obtain an UserToSkill object containing details of a usertoskill record based on the provided UserToSkillID
  *
  * @param    $userToSkillID        The UserToSkillID of the usertoskill object required
  * @return   $userToSkillObject    The instantiated usertoskill object containing the details of the usertoskill record with the given UserToSkillID
  * @access   public
  */
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

  /**
  * Function to obtain an Employment object containing details of an employment record based on the provided EmploymentID
  *
  * @param    $employmentID        The EmploymentID of the employment object required
  * @return   $employmentObject    The instantiated employment object containing the details of the employment record with the given EmploymentID
  * @access   public
  */
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

  /**
  * Mutator function to set $employeeIDToView field - used to set the Employee Number of the employee CV to be viewed
  *
  * @param    $employeeID   The Employee Number of the employee CV to be viewed
  * @access   public
  */
  public function setEmployeeIDToView($employeeID) {
    $this->employeeIDToView = $employeeID;
  }

  /**
  * Accessor function to get $employeeIDToView field - used to get the Employee Number of the employee CV to be viewed
  *
  * @return   $employeeID   The Employee Number of the employee CV to be viewed
  * @access   public
  */
  public function getEmployeeIDToView() {
    return $this->employeeIDToView;
  }

  /**
  * Mutator function to set $IDForGeneratePDF field - used to set the Employee Number of the employee CV to be exported to an Internal PDF
  *
  * @param    $employeeID   The Employee Number of the employee CV to be exported to an Internal PDF
  * @access   public
  */
  public function setIDForGeneratePDF($employeeID) {
    $this->IDForGeneratePDF = $employeeID;
  }

  /**
  * Accessor function to get $IDForGeneratePDF field - used to get the Employee Number of the employee CV to be exported to an Internal PDF
  *
  * @return   $employeeID   The Employee Number of the employee CV to be exported to an Internal PDF
  * @access   public
  */
  public function getIDForGeneratePDF() {
    return $this->IDForGeneratePDF;
  }

  /**
  * Mutator function to set $IDForGenerateExternalPDF field - used to set the Employee Number of the employee CV to be exported to an External PDF
  *
  * @param    $employeeID   The Employee Number of the employee CV to be exported to an External PDF
  * @access   public
  */
  public function setIDForGenerateExternalPDF($employeeID) {
    $this->IDForGenerateExternalPDF = $employeeID;
  }

  /**
  * Accessor function to get $IDForGenerateExternalPDF field - used to get the Employee Number of the employee CV to be exported to an External PDF
  *
  * @return   $employeeID   The Employee Number of the employee CV to be exported to an External PDF
  * @access   public
  */
  public function getIDForGenerateExternalPDF() {
    return $this->IDForGenerateExternalPDF;
  }

  /**
  * Function to set session variables based on the array of External PDF options provided by the user - used to save PDF customisable options
  *
  * @param    $externalPDFOptions   An array of customisable PDF options submitted by the user
  * @access   public
  */
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
