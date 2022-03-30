<?php

// ------ INCLUDES

require_once("constants.php");



// ------ HELPER METHODS

function echo_json(string $json): void
{
  header("Content-Type: application/json");
  echo $json;
}

function log_error(int $code, string $msg, string $error): void
{
  http_response_code(500);
  echo_json(json_encode([
    "code" => $code,
    "msg" => $msg,
    "error" => $error
  ]));

  exit($code);
}

function exception_handler($e)
{
  log_error(ERR_Exception, $e->getMessage(), $e->getTraceAsString());
}

set_exception_handler("exception_handler");