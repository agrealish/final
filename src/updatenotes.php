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

    //function to grab data from the form on updatenotes.php
    function updaten() {
      var noteForm = document.getElementById('updatenotes');
      var params = 'type=update';
      var params += '&id=' + noteForm.elements['taskid'].value;
      var params += '&note=' + noteForm.elements['notes'].value;
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
        <form id="updatenotes">
          <?php 
            echo '<input type="hidden" name="taskid" value=' . $_GET['id'] .'>';
          ?>
          <div id="notes">Enter Notes: <input type="text", name="notes"></div>
          <button type="button" onclick="updaten()">Update Status</button>
        </form>
        <br><br>
        <p>Return to the prior page by clicking <?php echo "<a href=" . $_REQUEST['backurl'] . ">" ?>here</a>.</p>
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