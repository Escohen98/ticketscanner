<?php
include("common.php");
//Inserts values into database. Use responsibly and only 1 time.
$db = get_PDO();
for($i = 0; $i<10000; $i++) {
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
    break;
  }

  if(isset($_GET["cmd"])) {
    if($_GET["cmd"] == "insert") {
    if($i == 0) {
      $db -> query("ALTER TABLE tickets AUTO_INCREMENT = 1");
    }
    $query = "INSERT INTO tickets (code) VALUES('{$code}')";
    print($code." ");
  } else if($_GET["cmd"] == "delete")
    $query = "DELETE FROM tickets";
  else {
      die("Incorrect command: cmd");
    }
  }
  else {
    die("Missing command: cmd");
  }
  $db -> query($query);
  //header("Content-type: text/plain")
}
print("Done.");
?>
