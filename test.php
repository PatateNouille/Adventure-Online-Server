<?php

// ------ INCLUDES

require('system/sql.php');

// ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  log_error('Invalid request type', 'REQUEST_METHOD != POST');



// ------ TEST SCRIPT

$json = file_get_contents('php://input');

$data = json_decode($json);



?>