<?php
include("common.php");
//-----------------Copyright of Eric Cohen -------------------------------------
if(isset($_POST["code"])) {
  echo json_encode(inactivate($_POST["code"])));

} else if(isset($_POST["pull"])) {
  echo json_encode(get_codes($_POST["pull"]));
}else {
  missing_param_msg(["code"]);
}

//Checks if the givewn $code is active.
function inactivate($code) {
  $db = get_PDO();
  if(get_exists($db, $code)) {
    $query = "UPDATE Tickets SET active=FALSE WHERE code='$code'";
    sql_query($db, $query);
    return ["exists" => true];
  } else {
    return ["exists" => false];
  }
}

//Pulls $count amount of codes and sets them to active.
function get_codes($count) {
  $db = get_PDO();
  if($count > 0 && count <= 10000) {
    $query = "SELECT code FROM Tickets WHERE active=FALSE LIMIT '$count'";
    $codes = implode(', ' sql_query($db, $query));
    //$query2 = "UPDATE Tickets SET active=TRUE WHERE active=FALSE LIMIT '$count'";
    $query2 = "UPDATE Tickets set active=TRUE WHERE code in ($codes)";
    sql_query($db, $query2);
  } else {
    handle_error("Invalid number, {$count} ")
  }
}

?>
