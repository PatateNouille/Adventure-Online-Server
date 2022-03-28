<?php

// ------ INCLUDES

require_once('system/constants.php');
require_once('system/system.php');
require_once('system/sql.php');



// ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  log_error(
    Error_Code::SERVER_InvalidRequestMethod,
    'Invalid request type', 'REQUEST_METHOD != POST');



// ------ TEST SCRIPT

$json = file_get_contents('php://input');

$data = json_decode($json);

?>