<?php //code received from lecture
error_reporting(E_ALL);
ini_set('display_errors','On');
header('Content-Type: text/html');
include 'storedInfo.php'; //from the lecture
session_start();

if(session_status()==PHP_SESSION_ACTIVE) {
  if(isset($_SESSION['username'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: logout.html");
    die();
  }
}
?>