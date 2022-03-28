<?php

// ------ INCLUDES

require_once('constants.php');



// ------ HELPER METHODS

function log_error(Error_Code $code, string $msg, string $error): void
{
  die(json_encode([
    'code' => $code,
    'msg' => $msg,
    'error' => $error
  ]));
}

?>