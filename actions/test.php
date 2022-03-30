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
  "type"
], "Request has invalid format");



if ($input["type"] == "test")
{
  assert_action_fields($input, [
    "session"
  ], "Request has invalid format");

  $session = Session::make_from_action($input["session"]);

  $output["[in] exists"] = $session->exists();
  $output["[in] time_left"] = $session->get_time_left();
  $output["[in] valid"] = $session->is_valid();
}



else if ($input["type"] == "open")
{
  assert_action_fields($input, [
    "account"
  ], "Request has invalid format");

  $session = Session::open($input["account"]);

  $output["[out] exists"] = $session->exists();
  $output["[out] time_left"] = $session->get_time_left();
  $output["[out] valid"] = $session->is_valid();
}

echo_json($output->json());