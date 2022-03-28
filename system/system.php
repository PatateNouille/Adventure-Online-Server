<?php

// ------ INCLUDES

require_once('constants.php');



// ------ HELPER METHODS

function log_error(int $code, string $msg, string $error): void
{
  die(json_encode([
    'code' => $code,
    'msg' => $msg,
    'error' => $error
  ]));
}

function exception_handler($e)
{
  log_error(ERR_Exception, $e->getMessage(), $e->getTraceAsString());
}

set_exception_handler('exception_handler');

?>