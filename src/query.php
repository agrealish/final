<?php //code received from lecture
//Having the php file output the table instead of trying to send the information back to the requesting page 
//came from this website: http://www.w3schools.com/php/php_ajax_database.asp
error_reporting(E_ALL);
ini_set('display_errors','On');
header('Content-Type: text/html');
include 'storedInfo.php'; //from the lecture
session_start();

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
if(!$mysqli || $mysqli->connect_errno) {
    echo "There was a database connection error. Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//this if statement determines which statements to execute based on the AJAX call
if (isset($_GET['type'])) {
  //determine if username and password are correct
  if($_GET['type'] === 'login') {
    $user = $_GET['user'];
    $pw = $_GET['pass'];
    if(!($stmt = $mysqli->prepare("SELECT username, pass FROM login WHERE username = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $user)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($credential = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    $row = $credential->fetch_assoc();
    if($row['username'] === $user) {
      if($row['pass'] === $pw) {
        $_SESSION['username'] = $user;
        $_SESSION['isCorrect'] = TRUE;
        checkPM($user);
        echo "<p>You have successfully logged in.</p>"
      }
      else {
        echo "<p>You have entered an incorrect password.</p>"
      }
    }
    else {
      echo "<p>You have entered an incorrect username.</p>"
    }
    $stmt->close();
  }
  
  //add a user
  if($_REQUEST['type'] === 'adduser') {
    $user = $_REQUEST['user'];
    $pw = $_REQUEST['pass'];
    $err = FALSE;
    if(strlen($user) > 30){
      echo "<p>Your username is too long. Keep it under 30 characters.</p>";
      $err = TRUE;
    }
    if(strlen($pw) > 30){
      echo "<p>Your password is too long. Keep it under 30 characters.</p>";
      $err = TRUE;
    }
    if($user === NULL || $user === "") {
      echo "<p>You didn\'t enter a username.  Try again.</p>"
      $err = TRUE;
    }
    if($pw === NULL || $pw === "") {
      echo "<p>You didn\'t enter a password.  Try again.</p>"
      $err = TRUE;
    }
    if(!$err) {
      if(!($stmt = $mysqli->prepare("SELECT username FROM login WHERE username = ?"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!$stmt->bind_param("s", $user)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!($credential = $stmt->get_result()) {
        echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
      }
      $row = $credential->fetch_assoc();
      $stmt->close();
      if($row['username'] === $user) {
        echo "<p>That username already exists, please try again.</p>"
      }
      else {
        if(!($stmt = $mysqli->prepare("INSERT INTO login(username, pass) values (?,?)"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if(!$stmt->bind_param("ss", $user, $pw)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if(!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        else {
          echo "<p>User successfully added. Please login.</p>"
        }
        $stmt->close();
      }
    }
  }
  
  //update the notes for a specific task
  if($_REQUEST['type'] === 'update') {
    if($_REQUEST['id'] === "") {
      echo "<p>Error: cannot update notes in task without knowing which task to update. Need task id.</p><br>"
    }
    else {
      $id = (int)$_REQUEST['id'];
      $notes = date("m.d.Y") . ": " . $_REQUEST['note'];
      if(!($stmt = $mysqli->prepare("INSERT INTO tasks(notes) VALUES (?) WHERE id = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!($stmt->bind_param("si", $notes, $id))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "Status successfully updated.";
      }
      $stmt->close();
    }
  }
  
  //update the notes on a task as the Project Manager
  if($_REQUEST['type'] === 'pmupdate') {
    if($_REQUEST['id'] === "") {
      echo "<p>Error: cannot update notes in task without knowing which task to update. Need task id.</p><br>"
    }
    else {
      $id = (int)$_REQUEST['id'];
      $notes = date("m.d.Y") . " PM note: " . $_REQUEST['note'];
      if(!($stmt = $mysqli->prepare("INSERT INTO tasks(notes) VALUES (?) WHERE id = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!($stmt->bind_param("si", $notes, $id))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "Status successfully updated.";
      }
      $stmt->close();
    }
  }
   
  //add a project to the database
  if($_REQUEST['type'] === 'addproj') {
    $pmname = $_REQUEST['pm'];
    $projname = $_REQUEST['pname'];
    $errprj = FALSE;
    if(strlen($projname) > 30) {
      echo "<p>Your project name is too long. Keep it under 30 characters.</p>";
      $errprj = TRUE;
    }
    if($projname === NULL || $projname === "") {
      "<p>You didn\'t enter a project name.  Try again.</p>"
      $errprj = TRUE;
    }
    if(!$errprj) {
      if(!($stmt = $mysqli->prepare("SELECT project FROM projects WHERE project = ?"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!$stmt->bind_param("s", $projname)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!($check = $stmt->get_result()) {
        echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
      }
      $row = $check->fetch_assoc();
      $stmt->close();
      if($row['project'] === $projname) {
        echo "<p>That project name already exists, please try again.</p>"
      }
      else {
        if(!($stmt = $mysqli->prepare("INSERT INTO projects(project, pm) values (?,?)"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if(!$stmt->bind_param("ss", $projname, $pmname)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if(!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        else {
          echo "<p>Project successfully added.</p>"
        }
        $stmt->close();
      }
    }
  }
  
  //add a task to the database
  if($_REQUEST['type'] === 'addtask') {
    $userid = $_REQUEST['user'];
    $taskname = $_REQUEST['tname'];
    $projname = $_REQUEST['pname'];
    $taskdesc = $_REQUEST['tdesc'];
    $duedate = $_REQUEST['due'];
    $priority = $_REQUEST['priority'];
    $tnotes = $_REQUEST['notes'];
    $errtask = FALSE;
    if(strlen($taskname) > 30) {
      echo "<p>Your task name is too long.  Keep it under 30 characters.</p>";
      $errtask = TRUE;
    }
    if($taskname === NULL || $taskname === "") {
      "<p>You didn\'t enter a task name.  Try again.</p>"
      $errtask = TRUE;
    }
    if(!$errtask) {
      if(!($stmt = $mysqli->prepare("SELECT name FROM tasks WHERE name = ?"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!$stmt->bind_param("s", $taskname)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!($check = $stmt->get_result()) {
        echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
      }
      $row = $check->fetch_assoc();
      $stmt->close();
      if($row['name'] === $taskname) {
        echo "<p>That task name already exists, please try again.</p>"
      }
      else {
        if(!($stmt = $mysqli->prepare("INSERT INTO tasks(user, name, project, desc, duedate, priority, notes) values (?,?,?,?,?,?,?)"))) {
          echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if(!$stmt->bind_param("sssssis", $userid, $taskname, $projname, $taskdesc, $duedate, $priority, $tnotes)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if(!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        else {
          echo "<p>Task successfully added.</p>"
        }
        $stmt->close();
      }
    }
  }

  //sort and print tasks due today or overdue
  if($_REQUEST['type'] === 'today') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT id, name, project, desc, duedate, priority, notes FROM tasks WHERE user = ? AND datedone = '' and duedate <= ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("ss", $username, $todaydt)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['id'] . ">";
      }
      echo "<td>" . $myrow['duedate'] . "</td>";
      echo "<td>" . $myrow['name'] . "</td>";
      echo "<td>" . $myrow['project'] . "</td>";
      echo "<td>" . $myrow['priority'] . "</td>";
      echo "<td>" . $myrow['desc'] . "</td>";
      echo "<td>" . $myrow['notes'] . "</td>";
      echo "<td><input type='button' onclick='updatenotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }

//sort and print tasks ordered by due date
  if($_REQUEST['type'] === 'date') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT id, name, project, desc, duedate, priority, notes FROM tasks WHERE user = ? AND datedone = '' ORDER BY duedate"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['id'] . ">";
      }
      echo "<td>" . $myrow['duedate'] . "</td>";
      echo "<td>" . $myrow['name'] . "</td>";
      echo "<td>" . $myrow['project'] . "</td>";
      echo "<td>" . $myrow['priority'] . "</td>";
      echo "<td>" . $myrow['desc'] . "</td>";
      echo "<td>" . $myrow['notes'] . "</td>";
      echo "<td><input type='button' onclick='updatenotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }

//sort and print according to priority
  if($_REQUEST['type'] === 'priority') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT id, name, project, desc, duedate, priority, notes FROM tasks WHERE user = ? AND datedone = '' ORDER BY priority"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Priority</th>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['id'] . ">";
      }
      echo "<td>" . $myrow['priority'] . "</td>";
      echo "<td>" . $myrow['duedate'] . "</td>";
      echo "<td>" . $myrow['name'] . "</td>";
      echo "<td>" . $myrow['project'] . "</td>";
      echo "<td>" . $myrow['desc'] . "</td>";
      echo "<td>" . $myrow['notes'] . "</td>";
      echo "<td><input type='button' onclick='updatenotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }

  //sort and print tasks ordered by project
  if($_REQUEST['type'] === 'project') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT id, name, project, desc, duedate, priority, notes FROM tasks WHERE user = ? AND datedone = '' ORDER BY project"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Project Name</th>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
  //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
  while($myrow = $result->fetch_assoc()) {
    if($myrow['duedate'] < $todaydt) {
      echo "<tr id = '" . $myrow['id'] . " bgcolor='#FF0000'>";
    }
    else {
      echo "<tr id = '" . $myrow['id'] . ">";
    }
    echo "<td>" . $myrow['project'] . "</td>";
    echo "<td>" . $myrow['duedate'] . "</td>";
    echo "<td>" . $myrow['name'] . "</td>";
    echo "<td>" . $myrow['priority'] . "</td>";
    echo "<td>" . $myrow['desc'] . "</td>";
    echo "<td>" . $myrow['notes'] . "</td>";
    echo "<td><input type='button' onclick='updatenotes.php'>Update Notes</button></td>";
    echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
    echo "</tr>";
  }
  echo "</table>";
  $stmt->close();
}

//sort and print tasks that are complete (order by when completed desc)
  if($_REQUEST['type'] === 'complete') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT id, name, project, desc, duedate, datedone, priority, notes FROM tasks WHERE user = ? AND datedone <> '' ORDER BY datedone DESC"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("ss", $username, $todaydt)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Date Completed</th>";
    echo "<th>Date Due</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      echo "<tr id = '" . $myrow['id'] . ">";
      echo "<td>" . $myrow['datedone'] . "</td>";
      echo "<td>" . $myrow['duedate'] . "</td>";
      echo "<td>" . $myrow['name'] . "</td>";
      echo "<td>" . $myrow['project'] . "</td>";
      echo "<td>" . $myrow['priority'] . "</td>";
      echo "<td>" . $myrow['desc'] . "</td>";
      echo "<td>" . $myrow['notes'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
  
  //sort and print tasks associated with project where due date is today or overdue
  if($_REQUEST['type'] === 'ptoday') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT tasks.id, tasks.user, tasks.name, tasks.project, tasks.desc, tasks.duedate, tasks.priority, tasks.notes FROM tasks INNER JOIN projects ON tasks.project=projects.project WHERE project.pm = ? AND tasks.datedone = '' and tasks.duedate <= ? ORDER BY tasks.duedate"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("ss", $username, $todaydt)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>User Responsible</th>"
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['tasks.id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['tasks.id'] . ">";
      }
      echo "<td>" . $myrow['tasks.duedate'] . "</td>";
      echo "<td>" . $myrow['tasks.name'] . "</td>";
      echo "<td>" . $myrow['tasks.project'] . "</td>";
      echo "<td>" . $myrow['tasks.user'] . "</td>";
      echo "<td>" . $myrow['tasks.priority'] . "</td>";
      echo "<td>" . $myrow['tasks.desc'] . "</td>";
      echo "<td>" . $myrow['tasks.notes'] . "</td>";
      echo "<td><input type='button' onclick='updatePMnotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
  
  //sort and print tasks associated with project by due date
  if($_REQUEST['type'] === 'pdate') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT tasks.id, tasks.user, tasks.name, tasks.project, tasks.desc, tasks.duedate, tasks.priority, tasks.notes FROM tasks INNER JOIN projects ON tasks.project=projects.project WHERE project.pm = ? AND tasks.datedone = '' and ORDER BY tasks.duedate"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>User Responsible</th>"
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['tasks.id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['tasks.id'] . ">";
      }
      echo "<td>" . $myrow['tasks.duedate'] . "</td>";
      echo "<td>" . $myrow['tasks.name'] . "</td>";
      echo "<td>" . $myrow['tasks.project'] . "</td>";
      echo "<td>" . $myrow['tasks.user'] . "</td>";
      echo "<td>" . $myrow['tasks.priority'] . "</td>";
      echo "<td>" . $myrow['tasks.desc'] . "</td>";
      echo "<td>" . $myrow['tasks.notes'] . "</td>";
      echo "<td><input type='button' onclick='updatePMnotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
  
  //sort and print tasks associated with project by user
  if($_REQUEST['type'] === 'user') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT tasks.id, tasks.user, tasks.name, tasks.project, tasks.desc, tasks.duedate, tasks.priority, tasks.notes FROM tasks INNER JOIN projects ON tasks.project=projects.project WHERE project.pm = ? AND tasks.datedone = '' and ORDER BY tasks.user"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>User Responsible</th>"
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['tasks.id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['tasks.id'] . ">";
      }
      echo "<td>" . $myrow['tasks.user'] . "</td>";
      echo "<td>" . $myrow['tasks.duedate'] . "</td>";
      echo "<td>" . $myrow['tasks.name'] . "</td>";
      echo "<td>" . $myrow['tasks.project'] . "</td>";
      echo "<td>" . $myrow['tasks.priority'] . "</td>";
      echo "<td>" . $myrow['tasks.desc'] . "</td>";
      echo "<td>" . $myrow['tasks.notes'] . "</td>";
      echo "<td><input type='button' onclick='updatePMnotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
  
  //sort and print tasks associated with project by priority
  if($_REQUEST['type'] === 'ppriority') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT tasks.id, tasks.user, tasks.name, tasks.project, tasks.desc, tasks.duedate, tasks.priority, tasks.notes FROM tasks INNER JOIN projects ON tasks.project=projects.project WHERE project.pm = ? AND tasks.datedone = '' and ORDER BY tasks.priority"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Priority</th>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>User Responsible</th>"
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "<th>Update Notes</th>";
    echo "<th>Complete Task</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      if($myrow['duedate'] < $todaydt) {
        echo "<tr id = '" . $myrow['tasks.id'] . " bgcolor='#FF0000'>";
      }
      else {
        echo "<tr id = '" . $myrow['tasks.id'] . ">";
      }
      echo "<td>" . $myrow['tasks.priority'] . "</td>";
      echo "<td>" . $myrow['tasks.duedate'] . "</td>";
      echo "<td>" . $myrow['tasks.name'] . "</td>";
      echo "<td>" . $myrow['tasks.project'] . "</td>";
      echo "<td>" . $myrow['tasks.user'] . "</td>";
      echo "<td>" . $myrow['tasks.desc'] . "</td>";
      echo "<td>" . $myrow['tasks.notes'] . "</td>";
      echo "<td><input type='button' onclick='updatePMnotes.php'>Update Notes</button></td>";
      echo "<td><input type='button' onclick='completeT(" . $myrow['id'] .")'>Complete Task</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
  
  //sort and print tasks associated with project only showing completed
  if($_REQUEST['type'] === 'pdate') {
    $todaydt = date("m.d.Y");
    $username = $_REQUEST['userid'];
    if(!($stmt = $mysqli->prepare("SELECT tasks.id, tasks.user, tasks.name, tasks.project, tasks.desc, tasks.duedate, tasks.datedone, tasks.priority, tasks.notes FROM tasks INNER JOIN projects ON tasks.project=projects.project WHERE project.pm = ? AND tasks.datedone <> '' and ORDER BY tasks.datedone DESC"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if(!$stmt->bind_param("s", $username)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!($result = $stmt->get_result()) {
      echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
    }
    echo "<table>";
    echo "<tr>";
    echo "<th>Date Completed</th>";
    echo "<th>Due Date</th>";
    echo "<th>Task Name</th>";
    echo "<th>Project Name</th>";
    echo "<th>User Responsible</th>"
    echo "<th>Priority</th>";
    echo "<th>Description</th>";
    echo "<th>Notes</th>";
    echo "</tr>";
    //read how to do this in comments on http://php.net/manual/en/mysqli-stmt.bind-result.php
    while($myrow = $result->fetch_assoc()) {
      echo "<tr id = '" . $myrow['tasks.id'] . ">";
      echo "<td>" . $myrow['tasks.datedone'] . "</td>";
      echo "<td>" . $myrow['tasks.duedate'] . "</td>";
      echo "<td>" . $myrow['tasks.name'] . "</td>";
      echo "<td>" . $myrow['tasks.project'] . "</td>";
      echo "<td>" . $myrow['tasks.user'] . "</td>";
      echo "<td>" . $myrow['tasks.priority'] . "</td>";
      echo "<td>" . $myrow['tasks.desc'] . "</td>";
      echo "<td>" . $myrow['tasks.notes'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
    $stmt->close();
  }
}
function checkPM($userid) {
  if(!($stmt = $mysqli->prepare("SELECT UNIQUE pm FROM projects WHERE pm = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  if(!$stmt->bind_param("s", $userid)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  if(!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  if(!($isPM = $stmt->get_result()) {
    echo "Getting results failed: (" . $stmt->errno . " " . $stmt->error;
  }
  $row = $credential->fetch_assoc();
  if($row['pm'] === $userid) {
    $_SESSION['pm'] = TRUE;
  }
}
?>
