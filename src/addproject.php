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
  </head>
  <script>
    var queryDB = function(querySt) {
      var req = new XMLHttpRequest();
      if(!req) {
        document.getElementById("error").innerHTML = "Unable to create HttpRequest";
      }
      //from lecture on AJAX
      req.onreadystatechange = function() {
        if(this.readyState === 4) {
          if(this.status === 200) {
            document.getElementById("main").innerHTML = req.responseText;
          }
          else {
            document.getElementById("error").innerHTML = "There was a problem with the query";
          }
        }
        
      }
      req.open('POST', 'http://web.engr.oregonstate.edu/~grealisa/CS290/final/query.php?' + querySt, true);
      req.send(querySt);
    }

    //function to grab data from the form on addproject.php
    function addproj() {
      var noteForm = document.getElementById('addproject');
      var params = 'type=addproj';
      var params += '&pm=' + noteForm.elements['pm'].value;
      var params += '&projname=' + noteForm.elements['pname'].value;
      queryDB(params);
    }
  </script>
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
        <p>Add your project below, note that you will automatically be included as the project manager.</p>
        <br>
        <form id="addproject">
          <?php 
            echo 'Project Manager: <input type="text", name="pm" value=' . $_SESSION['username'] .' readonly>';
          ?>
          <br>
          Project Name: <input type="text" name="pname">
          <button type="button" onclick="addproj()">Update Status</button>
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