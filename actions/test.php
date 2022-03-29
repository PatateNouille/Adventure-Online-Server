<?php

// ------ INCLUDES

require_once('../system/constants.php');
require_once('../system/system.php');
require_once('../system/sql.php');
require_once('../system/action.php');



// ------ ERROR HANDLING

assert_request_method();



// ------ TEST SCRIPT

$input = new ActionInput();
$output = new ActionOutput();

try
{
  $input->assert_fields([
    'field'
  ]);
}
catch (ArgumentCountError $e)
{
  log_error(
    ERR_SERVER_InvalidRequestFormat,
    'Request has invalid format',
    $e->getMessage()
  );
}

echo $output->to_string();