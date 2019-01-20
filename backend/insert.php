<?php
include("common.php");
//Inserts values into database. Use responsibly and only 1 time.
$db = get_PDO();
if(isset($_GET["cmd"])) {
  if($_GET["cmd"] == "insert") {
    for($i = 0; $i<10000; $i++) {
      if(insert_db($db, $i) == 6) {
        break;
      }
    }
  } else if($_GET["cmd"] == "delete") {
    $query = "DELETE FROM tickets";
    $db -> query($query);
    } else {
      die("Incorrect command: cmd");
    }
} else {
  die("Missing command: cmd");
}
print("Done.");

function insert_db($db, $i) {
  $sli = strlen("{$i}");
  $code = "";
  if ($sli == 1) {
      $code = "000{$i}";
  } else if($sli == 2) {
      $code = "00{$i}";
  } else if($sli == 3) {
      $code = "0{$i}";
  } else if($sli == 4) {
      $code = "{$i}";
  } else {
    return 6;
  }
  if($i == 0) {
    $db -> query("ALTER TABLE tickets AUTO_INCREMENT = 1");
  }
  $query = "INSERT INTO tickets (code) VALUES('{$code}')";
  print($code." ");
  $db -> query($query);
  return;
}
?>
