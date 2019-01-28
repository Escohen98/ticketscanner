<?php
include("common.php");
//-----------------Copyright of Eric Cohen -------------------------------------
if(isset($_POST["code"]) && intval(file_get_contents("./bin/auth.txt")) == 1) {
  echo json_encode(inactivate($_POST["code"]));
} else if(isset($_POST["pull"]) && isset($_POST["password"])) {
  if(check_password($_POST["password"])) { //Asks for password 1 time after each pull request.
    echo json_encode(get_codes($_POST["pull"]));
    file_put_contents("./bin/auth.txt", "0"); //Resets password validator.
  } else {
    echo json_encode(["codes" => "Incorrect password."]);
  }
} else if(isset($_POST["password"])) { //Asks for password 1 time before accessing scanner.html
  if(check_password($_POST["password"], 0) == "a") {
    file_put_contents("./bin/auth.txt", "1");
    echo json_encode(["correct" => true]);
  } else {
    echo json_encode(["correct" => check_password($_POST["password"], 1)]);
  }
} else {
  $array = array();
  array_push($array, "code");
  array_push($array, "pull and /");
  array_push($array, "password");
  $msg = "Missing parameters: code or pull and/or password.";
   die(json_encode(array("error" => $msg)));
}

//Checks if the givewn $code is active. It is, returns true and deactivates.
//Otherwise returns false.
function inactivate($code_encode) {
  $code = decode($code_encode);
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
function get_codes($countS) {
  $count = intval($countS);
  $db = get_PDO();
  if($count > 0 && $count <= 10000) {
    $query = "SELECT code FROM tickets WHERE active=0 LIMIT {$count}";
    $output = $db -> query($query);
    $codes = array();
    foreach($output as $c) {
      array_push($codes, $c["code"]);
    //  $query2 = "UPDATE tickets SET active=1 WHERE code = {$c['code']}";
    //  $db -> query($query2);
    }
    return ["codes" => create_codes($codes)];
  } else {
    handle_error("Invalid number, {$count} ");
  }
}

//Generates randomized code strings from the response. Returns result in array.
function create_codes($codes) {
  $CODE_LENGTH = 17;
  $output = array();
  for($i=0; $i<count($codes); $i++) {
    $index = rand(0, $CODE_LENGTH-7);
    $code_string = "";
    for($j=0; $j<$CODE_LENGTH; $j++) {
      if($j == $index) {
        $code = $codes[$i];
        $code1 = substr($code, 0, 2);
        $code2 = substr($code, 2, 4);
        print($code);
        $code_string .= "x{$code1}x{$code2}x";
        //$code_string += $codes[$i];
        $j+=6;
        continue;
      }
      //48-57;10 , 65-90;26, 97-122;26
      //Random character from 48-90 (0-Z)
      $num = rand(0, 62);
      while($num == 18) {
        $num = rand(0, 62);
      }
      if($num < 10) { //0-9
        $code_string .= chr($num + 48);
      } else if($num > 36) { //a-z
        $code_string .= chr($num + 60);
      } else { //A-Z
        $code_string .= chr($num + 55);
      }
    }
    array_push($output, $code_string);
  }
  return $output;
}

//Extracts the code from the code string.
function decode($code) {
  $index = strpos('x');
  return substr($code, $index+1, 2).substr($code, $index+4, 2);
}

//Checks if entered password is correct.
//File used so user can't bypass password.
function check_password($input, $index=1) {
  return file("./bin/passwords.txt")[$index] == $input;
}

?>
