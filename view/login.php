<?php

/**
*
* PHP Version 7.4.3
*
* @author   Akeeb Saberi (saberia@aston.ac.uk), Aston University Candidate Number 991554
*
*/

#Start session if not already started
if (!isset($_SESSION)) {
  session_start();
}

//Get user when logging in
if (isset($_POST["login"])) {
	if ($this->model->getUserByUsernameAndPassword($_POST["username"], $_POST["password"])){
		$userToLogin = $this->model->getUserByUsernameAndPassword($_POST["username"], $_POST["password"]);
    //Check for successful login
		if ($userToLogin != null) {
      //Display welcome message
      ?>
      <div class="container">
			  <b><h3>Hello <?php echo "$userToLogin->firstName" ?> </h3></b>
      </div>
      <?php
		}
		else {
      //Display error message
      ?>
      <div class="container">
			  <p>Sorry, error logging in</p>
      </div>
      <?php
		}
	}
}
else {
//No post request from webpage, display login form as normal
?>
<!--Login form for the user to use to login-->
<div class="container">
  <h1>Login</h1>
  <form method="post" action="">
    <div>
      <label for="loginUsername">Please enter your username</label>
  		<input type="text" name="username" id="loginUsername"/> <br><br>
  		<label for="loginPassword">Please enter your password</label>
  		<input type="password" name="password" id="loginPassword"/>
      <input type="submit" name="login" value="login">
  	</div>
  </form>
</div>

<?php
}
?>
