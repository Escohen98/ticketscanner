<?php
//Secret
  if(isset($_POST["type"])) {
    $type = $_POST["type"];
    header("Content-type: application/json");
    if($type == 0) {
      json_encode(["password" => "g3t_t!cK37s"]);
    } else if($type == 1) {
      json_encode(["password" => "SoujaBoy2k!9"]);
    } else {
      json_encode(["password" => "invalid"]);
    }
  } else {
    header("Content-type: text/plain");
    die("Invalid or missing parameter(s).");
  }
?>
