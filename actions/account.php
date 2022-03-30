<?php

// ------ INCLUDES

require_once("../system/constants.php");
require_once("../system/system.php");
require_once("../system/sql.php");
require_once("../system/action.php");



// ------ ERROR HANDLING

assert_request_method();



// ------ TEST SCRIPT

$input = ActionInput::make_from_post_body();
$output = new ActionOutput();

assert_action_fields($input, [
  "log_type",
  "username",
  "password"
], "Request has invalid format");



$name = htmlspecialchars($input["username"]);
$pswd = htmlspecialchars($input["password"]);

switch ($input["log_type"])
{
  default:
    log_error(
      ERR_ACC_InvalidLogType,
      "Invalid log type", "Expected REGISTER or LOGIN");
    break;



  case "REGISTER":
    {
      $result = query(
        "SELECT id FROM accounts WHERE username=?",
        "s",
        $name
      );

      if ($result->num_rows != 0)
        log_error(
          ERR_ACC_UsernameNotUnique,
          "Username not unique", "An account with that username already exists");

      $hash = password_hash($pswd, PASSWORD_DEFAULT);
      
      $result = query(
        "INSERT INTO accounts(username, password) VALUES (?, ?)",
        "ss",
        $name, $hash);
    }
    


  case "LOGIN":
    {
      $result = query(
        "SELECT id,password FROM accounts WHERE username=?",
        "s",
        $name);
      
      $obj = $result->fetch_object();
      
      $input["id_account"] = password_verify($pswd, $obj->password) ? $obj->id : -1;
    }
    break;
}

echo $output->json();