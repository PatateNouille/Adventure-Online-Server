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
  "session",
  "id"
], "Request has invalid format");

$session = Session::make_from_action($input["session"]);

if (!$session->is_valid())
    log_error(
        ERR_SES_InvalidSession,
        "Session is invalid", "The session provided is either invalid or expired, you may need to log in again"
    );

$id = htmlspecialchars($input["id"]);

$result = query(
    "SELECT * FROM encounter WHERE id = ?",
    "i",
    $id
);

if ($result->num_rows == 0)
    log_error(
        ERR_SERVER_InvalidRequestFormat,
        "Invalid encounter ID", "No encounter exist with that ID"
    );

$encounter = $result->fetch_object();

$output["monsterBudget"] = $encounter->monster_factor;
$output["resourceBudget"] = $encounter->resource_factor;
$output["levelSize"] = $encounter->level_size;

echo_json($output->json());