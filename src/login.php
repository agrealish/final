<?php //code received from lecture
//Having the php file output the table instead of trying to send the information back to the requesting page 
//came from this website: http://www.w3schools.com/php/php_ajax_database.asp
error_reporting(E_ALL);
ini_set('display_errors','On');
header('Content-Type: text/html');
include 'http://web.engr.oregonstate.edu/~grealisa/CS290/final/storedInfo.php'; //from the lecture
session_start();

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
if(!$mysqli || $mysqli->connect_errno) {
    echo "There was a database connection error. Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
 if (isset($_GET['type'])) {
  $type = $_GET['type'];
  $user = $_GET['user'];
  $pw = $_GET['pass'];
  if($type === 'login'){ 
    if(!($stmt = $mysqli->prepare("SELECT * FROM login WHERE 'username' = ?"))) {
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
  else {
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
      if(!($stmt = $mysqli->prepare("SELECT * FROM login WHERE username = ?"))) {
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
}
function checkPM($userid) {
  if(!($stmt = $mysqli->prepare("SELECT * FROM projects WHERE pm = ?"))) {
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