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

$session = Session::make_from_action($input);

$output["valid"] = $session->is_valid();
$output["near_expired"] = $session->get_time_left() < SESSION_NearExpired;

echo_json($output->json());