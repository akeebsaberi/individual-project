<!DOCTYPE html>

<?php
  //Start session if it has not been started already
  if (!isset($_SESSION)) {
    session_start();
  }
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Individual Project</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

  </head>
  <body>

    <!-- Global bar at the top of every page for branding and session confirmation -->
    <nav id="main-header" class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <div class="navbar-brand">Silhouette</div>
        </div>
        <div class="navbar-right">
          <?php
            //If there is an authenticated user, display their full name in the top right of the global bar
            if (isset($_SESSION['user']['Username']) && !empty($_SESSION['user']['Username'])) {
              echo '<div class="navbar-brand">' . $_SESSION['user']['FirstName'] . ' ' . $_SESSION['user']['Surname'] . '</div>';
            }
          ?>
        </div>
      </div>
    </nav>

    <!-- Global navbar for navigation across the web application-->
    <nav id="menu-section" class="navbar navbar-default">
      <div class="container">
        <ul class="nav nav-pills">
          <!--Login - all users -->
          <li class="navtop"><a href="?page=login">Login</a></li>

          <!--View my cv - all users -->
          <li class="navtop"><a href="?page=viewmycv">View My CV</a></li>

          <!--Logout -->
          <li class="navtop"><a href="?page=logout">Logout</a></li>
        </ul>
      </div>
   </nav>

   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
   <!-- Bootstrap JS -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  </body>
</html>
