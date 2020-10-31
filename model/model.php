<?php

class Model {

  //fields for database connection config
  private $server;
  private $dbname;
  private $username;
  private $password;
  private $pdo;

  //constructor for Model class
  public function __construct($server, $dbname, $username, $password) {
    $this->pdo = null;
    $this->server = $server;
    $this->dbname = $dbname;
    $this->username = $username;
    $this->password = $password;
  }

  //connect function to create a db connection
  public function connect() {
    try {
      $this->pdo = new PDO("mysql:host=$this->server;dbname=$this->dbname", "$this->username", "$this->password");
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $ex) {
      echo "<p> a database error occurred: <em> <?= $ex->getMessage() ?> </em></p>";
    }
  }

}

?>
