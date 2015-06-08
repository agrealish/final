<?php //code received from lecture
error_reporting(E_ALL);
ini_set('display_errors','On');
header('Content-Type: text/html');
include 'storedInfo.php'; //from the lecture
session_start();

//check if logged in, and redirect - this is the login page
  if(session_status()==PHP_SESSION_ACTIVE) {
    if(!isset($_SESSION['username'])){
      header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Project Management Task List</title>
    <link rel="stylesheet" href="style.css">
    <script src='js/projects.js'></script>
  </head>
  <body>
    <!--Div structure information found on http://www.456bereastreet.com/lab/developing_with_web_standards/csslayout/2-col/ -->
    <div id="wrap">
      <div id="header">
        <img src="clipboard.JPG">
        <h1>Project Management Task List</h1>
      </div>
      <div id="main">
      <?php 
      if(!(isset($_SESSION['pm']) && $_SESSION['pm'] === TRUE)) {
        echo "<p>You need to be a Project Manager to see this area.</p>";
        echo "<p>Please click <a href='main.php'>here</a> to return to the main page.</p>";
      }
      else {
      ?>
      <!-- If PM, put the code to show the projects here: -->
      
      <?php
      }
      ?>
      </div>
      <div id="sidebar">
        <a href="main.php">Home</a>
        <br><a href="addtask.php">Add Task</a>
        <br><a href="tasks.php">View Your Tasks</a>
        <br><a href="addproject.php">Add Project</a>
        <br><a href="projects.php">Manage Your Projects</a>
        <br>     <a href="tasks.php&sort=ptoday">Today's Tasks</a>
        <br>     <a href="tasks.php&sort=pdate">By Due Date</a>
        <br>     <a href="tasks.php&sort=user">By Assigned User</a>
        <br>     <a href="tasks.php&sort=ppriority">By Priority</a>
        <br>     <a href="tasks.php&sort=pcomplete">Completed Tasks</a>
        <br>     <a href="addptask.php">Add a Task to Project</a>
        <br><br><a href="logout.php">Logout</a>
      </div>
      <div id="footer">
        <p>CS290 Final Project</p>
      </div>
    </div>
  </body>
</html>
<?php 
  }
?>