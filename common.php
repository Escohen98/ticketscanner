<?php
  /*
  * Eric Cohen
  * Date: December 6, 2018
  * Section: CSE 154 AI
  *
  * A parent file that includes functions to be used throughout multiple PHP
  * files. This file also declares the header type and creates the PDO object
  * required to communicate with the MySQL server.
  */

  /*
   * Taken from
   * Week 9 Section: PHP and SQL
   * Configuration/common file for the Bricker and Morter Store API
   */

  # These two statements are needed to properly set strict error-reporting
  # on MAMP servers
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  header("Content-type: application/json");
  /**
   * Returns a PDO object connected to the bmstore database. Throws
   * a PDOException if an error occurs when connecting to database.
   * @return {PDO}
   */
   function get_PDO() {
     # Variables for connections to the database.
     $host =  "localhost";
     $port = "8889"; # Make sure this matches your server (e.g. MAMP) port
     $user = "root";
     $password = "root";
     $dbname = "zbt_tickets";

     # Make a data source string that will be used in creating the PDO object
     $ds = "mysql:host={$host}:{$port};dbname={$dbname};charset=utf8";

     try {
       $db = new PDO($ds, $user, $password);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       return $db;
     } catch (PDOException $ex) {
       cannot_connect_db($ex);
     }
   }


//-----------------Not taken from Class: ---------------------------------------


   //Returns fail to connect to database message in an array.
   //@param $ex {PDOException} - The PDO exception.
   //@return {array} - An associative array containing a cannot connect to
   //                  database message message.
   function cannot_connect_db($ex) {
      die(json_encode(array("error" => "Can not connect to the database.\n
                            Details:".$ex)));
   }

   //Inserts given data into Pokedex table in hw5 if the pokemon has not been
   //found. Inserts pokemon name in all lowercase. Prints error if failed to
   //insert.
   //@param $name {String} - Name of the given pokemon to be added to the
   //                        database.
   //@param $nickname {String} - [Optional] Nickname of the given pokemon to be
   //                            added to the database. Default = null.
   //@param $from_insert {boolean} - TRUE if the request comes from insert.php.
   //                                FALSE if from trade.php. Default = FALSE.
   //@return error {array} - An associative array containing an error message
   //                        that the pokemon has been found. returned if
   //                        pokemon is in database already.
   //@return success {array} - An associative array containing a success message
   //                          that the pokemon's information has been inserted
   //                          into the database successfully.
   function insert_table($name, $nickname=null, $from_insert=FALSE) {
     date_default_timezone_set('America/Los_Angeles');
     $time = date('y-m-d H:i:s'); //Using as specified in spec.
     $data = array(":name" => $name, ":nickname" => $nickname,
                   ":datefound" => $time);
     try {
       $db = get_PDO();
       //So I can strtolower the data.
       $replies = array(
         1 => array("success" => "Success! {$data[':name']} added to your
                    Pokedex!"),
         2 => array("error" => "Error: Pokemon {$data[':name']} already found.")
         ,3 => array("success" => "https://bit.ly/19Sfw9e")
       );

       //User does not input name. No risk of injection.
       if(get_exists($db, $data[":name"])) {
         return $replies[2];
       }

       return run_query($db, $data, $replies, $from_insert);
     } catch(PDOException $ex) {
       cannot_connect_db($ex);
     }
   }

   //A query handler to insert pokemon data into the given database.
   //@param $db - A PDO object allowing a connection to the MySQL server.
   //@param $data associative array} - The parameters required for the query.
   //@param $replies {array} - An array containing the associative arrays of the
   //                       success/error messages that are to be returned.
   //@param $from_insert {boolean} - TRUE if the function call is from
   //                             insert.php. FALSE otherwise.
   //@return replies[1] {array} - An array containing a success message
   //                          that the pokemon's information has been inserted
   //                          into the database successfully.
   //@return replies[2] {array} - An associative array containing a success
   //                          message to be modified later.
   function run_query($db, $data, $replies, $from_insert) {
     $data[":name"] = strtolower($data[":name"]);
     $query = "INSERT INTO Pokedex (name, nickname, datefound) VALUES
              (:name, :nickname, :datefound)";
     sql_query($db, $query, $data);
     if($from_insert) {
       return $replies[1];
     } else {
       return $replies[3];
     }
   }

   //Sends a request to database to see if the given code exists in the query.
   //@param $code {string} - The 4 digit numeric string code.
   //@param $db - A PDO object allowing a connection to the MySQL server.
   //@return {boolean} - Returns true if the name already exists in the table.
   //                    returns false otherwise.
   function get_exists($db, $code) {
     try {
       $query = "SELECT code AS {$code} FROM Tickets;";
       $result = $db -> query($query);
       $output = array();
       foreach($result as $row) {
         array_push($output, ["isEmpty" => $row]);
       }
       return sizeof($output) >= 1;
     } catch (PDOException $ex) {
       cannot_connect_db($ex);
     }
     return;
   }

   //Returns a missing parameter message in an array.
   //@param $params {array} or {$String} - An array or String of the missing
   //                                      parameter name(s).
   //@param $both {boolean} - True if both/all parameters are required. False
   //                          if only 1 parameter is required. default = null.
   //@return msg {array} - An associative array containing a missing paremeter
   //                      message.
   function missing_param_msg($params, $both=NULL) {
     $msg = "Missing ";
     if(gettype($params) == gettype("")) {
       $msg .= "{$params} parameter.";
      } else {
       foreach($params as $param) {
          if($both) {
            $msg .= " {$param} and";
          } else {
            $msg .= " {$param} or";
          }
       }
       $msg = substr($msg, 0, sizeof($msg)-4)." parameter.";
     }
     die(json_encode(array("error" => $msg)));
   }

   //Deletes the row in the given database that has the given pokemon name.
   //Returns response message.
   //@param $db - A PDO object allowing a connection to the MySQL server.
   //@param $name {String} - The name of the pokemon to be removed from the
   //                        database
    //@return error {array} - An associative array containing an error message
    //                        that the pokemon has been found. returned if
    //                        pokemon is in database already.
    //@return success {array} - An associative array containing a success message
    //                          that the pokemon's information has been removed
    //                          from the database successfully.
   function delete_table_content($db, $name) {
     if(!get_exists($db, $name)) {
       return array("error" => "Error: Pokemon {$name} not found in your Pokedex
       .");
     }
     try {
       $lower_name = strtolower($name);
       $query = "DELETE FROM Pokedex WHERE name = '$lower_name'";
       sql_query($db, $query);
       return array("success" => "Success! {$name} removed from your pokedex");
     } catch(PDOException $ex) {
       cannot_connect_db($ex);
     }
   }

   //Runs a secure SQL query for POST requests.
   //@param $db - A PDO object allowing a connection to the MySQL server.
   //@param $query {String} - The MySQL query to be executed.
   //@param $data {associative array} - The parameters required for the query.
   //                                   default = null.
  function sql_query($db, $query, $data=null) {
    $stmt = $db -> prepare($query);
    if($data) {
    $stmt -> execute($data);
  } else {
    $stmt -> execute();
  }
  }

  /**
    * Prints out a plain text 400 error message given $msg. If given a second
    * (optional) argument as
    * an PDOException, prints details about the cause of the exception.
    * @param $msg {string} - Plain text 400 message to output
    * @param $ex {PDOException} - (optional) Exception object with additional
    * exception details to print
    */
   function handle_error($msg, $ex=NULL) {
     header("HTTP/1.1 400 Invalid Request");
     header("Content-type: text/plain");
     die ("{$msg}\n");
     if ($ex) {
       die ("Error details: $ex \n");
     }
   }
?>
