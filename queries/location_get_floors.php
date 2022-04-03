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

$loc_id = htmlspecialchars($input["id"]);

$result = query(
    "SELECT * FROM floor WHERE id_location = ? ORDER BY floor ASC",
    "i",
    $loc_id
);

$floors = array();

while ($floor = $result->fetch_object())
{
    $floors []= [
        "id" => $floor->id,
        "floor" => $floor->floor,
        "encounterID" => $floor->id_encounter,
        "campID" => $floor->id_camp
    ];
}

$output["floors"] = $floors;

echo_json($output->json());