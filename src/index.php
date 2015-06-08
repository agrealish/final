<?php //code received from lecture
error_reporting(E_ALL);
ini_set('display_errors','On');
header('Content-Type: text/html');
session_start();

//check if logged in, and redirect - this is the login page
  if(session_status()==PHP_SESSION_ACTIVE) {
    if(isset($_SESSION['username'])){
      if(isset($_SESSION['isCorrect']) && $_SESSION['isCorrect'] == TRUE) {
        header("Location: main.php");
      }
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Project Management Task List</title>
    <link rel="stylesheet" href="style.css">
    <script src='js/login.js'></script>
  </head>
  <body>
    <!--Div structure information found on http://www.456bereastreet.com/lab/developing_with_web_standards/csslayout/2-col/ -->
    <div id="wrap">
      <div id="header">
        <img src="clipboard.JPG">
        <h1>Project Management Task List</h1>
      </div>
      <div id="main">
        <div id="error">
        </div>
        <br><br>
        <p>Welcome to the Project Management Task List, a tool to help you manage your project-related tasks and to facilitate communication on current status of project tasks between the project manager and those working on the project.</p>
        <br><p>Please use the forms below to either login or register as a new user.</p>
        <br><br>
        <form id="loginform">
          Username: <input type="text" name="username" placeholder="Username">
          <br>
          Password: <input type="password" name="password" placeholder="Password">
          <br>
          <button type="button" onclick="loginDB()">Sign In</button>
        </form>
        <br><br>
        <form id="adduser">
          Username: <input type="text" name="username" placeholder="Username">
          <br>
          Password: <input type="password" name="password" placeholder="Password">
          <br>
          <button type="button" onclick="adduserDB()">Register</button>
        </form>
      </div>
      <div id="sidebar">
        <a href="main.php">Home</a>
        <br><a href="addtask.php">Add Task</a>
        <br><a href="tasks.php">View Your Tasks</a>
        <br><a href="addproject.php">Add Project</a>
        <br><a href="projects.php">Manage Your Projects</a>
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