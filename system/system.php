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

?>