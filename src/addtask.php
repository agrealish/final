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
      req.open('GET', 'http://web.engr.oregonstate.edu/~grealisa/CS290/final/query.php?' + querySt, true);
      req.send();
    }

    function taskadd() {
      var noteForm = document.getElementById('addtask');
      var params = 'type=addtaskdb';
      var params += '&user=' + noteForm.elements['userident'].value;
      var params += '&tname=' + noteForm.elements['taskname'].value;
      var params += '&pname=' + noteForm.elements['projname'].value;
      var params += '&tdesc=' + noteForm.elements['taskdesc'].value;
      var params += '&due=' + noteForm.elements['duedt'].value;
      var params += '&priority=' + noteForm.elements['priority'].value;
      var params += '&notes=' + noteForm.elements['notes'].value;
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
        <p>Please use the form below to add tasks to the database.</p>
        <br>
        <form id="addtask">
          <?php 
            echo 'User: <input type="text" name="userident" value=' . $_SESSION['username'] .' readonly>';
          ?>
          Task Name: <input type="text" name="taskname" required>
          Project: <select name="projname">
            <?php 
              $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
              if(!$mysqli || $mysqli->connect_errno) {
                echo "There was a connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
              }
              $projects = $mysqli->query("SELECT DISTINCT project FROM projects ORDER BY project");
              
              while ($proj = $projects->fetch_assoc()){
                if(!is_null($proj['project'])) {
                  echo "<option value='" . $proj['project'] . "'>" . $proj['project'] . "</option>";
                }
              }
            ?>
          </select>
          <div id="description">Description: <input type="text" name="taskdesc"></div>
          Date Due: <input type="date" name="duedt">
          <select name="priority">
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
          </select>
          <div id="notes">Enter Notes: <input type="text" name="notes"></div>
          <button type="button" onclick="taskadd()">Add Task</button>
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