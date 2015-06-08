  <?php
    ini_set('display_errors', 'On'); //from the lecture
    include 'storedInfo.php'; //from the lecture
    
    //connection sequence is from the lecture
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
    if(!$mysqli || $mysqli->connect_errno) {
      echo "There was a connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    
    $mysqli->query("CREATE TABLE tasks (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, name VARCHAR(30) NOT NULL UNIQUE, project VARCHAR(30) NOT NULL UNIQUE, desc VARCHAR(255), duedate DATE, datedone DATE, priority TINYINT(1), notes LONGTEXT)");
    //$mysqli->query("CREATE TABLE login (username VARCHAR(30) NOT NULL PRIMARY KEY UNIQUE, pass VARCHAR(30) NOT NULL)");
    

$mysqli->close();
?>