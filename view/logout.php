<?php
//Start session if not already started
if (!isset($_SESSION)) {
  session_start();
}

//Destroys session, removes store of user object
session_destroy();
//Confirmation message to inform the user that they are no longer logged in
?>

<div class="container">
  <h1>Logout</h1>
  <p>You have successfully logged out.</p>
</div>
