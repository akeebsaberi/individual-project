<?php

include_once("model/user.php");

class Model {

  //Fields for database connection config
  private $server;
  private $dbname;
  private $username;
  private $password;
  private $pdo;

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
          $user=new User($row["EmployeeNumber"], $row["FirstName"], $row["Surname"], $row["Username"], $row["Password"], $row["IsResourceManager"], $row["IsAdmin"], $row["DateOfBirth"], $row["BaseLocation"], $row["LineManager"], $row["ReviewerManager"], $row["ResourceManager"], $row["BusinessUnit"], $row["Grade"]);
          //Set session variables for authenticated user
          $_SESSION['user']['EmployeeNumber'] = $user->employeeNumber;
          $_SESSION['user']['FirstName'] = $user->firstName;
          $_SESSION['user']['Surname'] = $user->surname;
          $_SESSION['user']['Username'] = $user->username;
          $_SESSION['user']['Password'] = $user->password;
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

}

?>
