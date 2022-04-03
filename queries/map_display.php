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

$output = new ActionOutput();


// TODO: use the airship position
$result = query("SELECT * FROM location");

$locs = array();
while ($loc = $result->fetch_object())
{
    $locs []= 
    [
        "pos" => [ "x" => $loc->pos_x, "y" => $loc->pos_y ],
        "name" => $loc->name,
        "levelType" => $loc->id_level_type
    ];
}

$output["locations"] = $locs;

echo_json($output->json());