<?php
include("common.php");
//-----------------Copyright of Eric Cohen -------------------------------------
if(isset($_POST["code"])) {
  echo json_encode(inactivate($_POST["code"]));

} else if(isset($_POST["pull"])) {
  echo json_encode(get_codes($_POST["pull"]));
} else {
  $array = array();
  array_push($array, "code");
  array_push($array, "pull");
  missing_param_msg($array, false);
}

//Checks if the givewn $code is active. It is, returns true and deactivates.
//Otherwise returns false.
function inactivate($code) {
  $db = get_PDO();
  $query = "SELECT active FROM tickets WHERE code={$code}";
  $isActive = $db -> query($query);
  foreach($isActive as $a) {
    $isActive = $a["active"];
  }
  if($isActive == "1") {
    $query = "UPDATE tickets SET active=0 WHERE code={$code}";
    sql_query($db, $query);
    return ["active" => true];
  } else {
    return ["active" => false];
  }
}

//Pulls $count amount of codes and sets them to active.
function get_codes($count) {
  $db = get_PDO();
  if($count > 0 && $count <= 10000) {
    $query = "SELECT code FROM tickets WHERE active=0 LIMIT {$count}";
    $output = $db -> query($query);
    $codes = array();
    foreach($output as $c) {
      array_push($codes, $c["code"]);
      $query2 = "UPDATE tickets set active=1 WHERE code = {$c['code']}";
      $db -> query($query2);
    }
    return ["codes" => $codes];
  } else {
    handle_error("Invalid number, {$count} ");
  }
}

?>
