<?php
include("common.php");
//-----------------Copyright of Eric Cohen -------------------------------------
if(isset($_POST["code"])) {
  echo json_encode(inactivate($_POST["code"])));

} else {
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

?>
