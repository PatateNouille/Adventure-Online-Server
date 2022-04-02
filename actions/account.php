<?php

// ------ INCLUDES

require_once("../system/constants.php");
require_once("../system/system.php");
require_once("../system/sql.php");
require_once("../system/action.php");
require_once("../system/session.php");



// ------ ERROR HANDLING

assert_request_method();



// ------ TEST SCRIPT

$input = ActionInput::make_from_post_body();
$output = new ActionOutput();

assert_action_fields($input, [
  "logType",
  "username",
  "password"
], "Request has invalid format");



$name = htmlspecialchars($input["username"]);
$pswd = htmlspecialchars($input["password"]);

switch ($input["logType"])
{
  default:
    log_error(
      ERR_ACC_InvalidLogType,
      "Invalid log type", "Expected REGISTER or LOGIN"
    );
    break;



  case "REGISTER":
    {
      $result = query(
        "SELECT id FROM account WHERE username=?",
        "s",
        $name
      );

      if ($result->num_rows != 0)
        log_error(
          ERR_ACC_UsernameNotUnique,
          "Username not unique", "An account with that username already exists"
        );

      $hash = password_hash($pswd, PASSWORD_DEFAULT);
      
      $result = query(
        "INSERT INTO account(username, password) VALUES (?, ?)",
        "ss",
        $name, $hash
      );
    }
    


  case "LOGIN":
    {
      $result = query(
        "SELECT id,password FROM account WHERE username=?",
        "s",
        $name
      );
      
      $account = $result->fetch_object();
      
      if (!$account || !password_verify($pswd, $account->password))
        log_error(
          ERR_ACC_InvalidCredentials,
          "Invalid credentials", "The username and/or the password are/is incorrect"
        );
      
      $session = Session::open($account->id);

      $output["id"] = $session->get_id();
      $output["token"] = $session->get_token();
    }
    break;
}

echo $output->json();