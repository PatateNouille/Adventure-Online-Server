<?php

// ------ ERROR CODES

$const_idx = 0;

// -- Global
define("ERR_Exception", ++$const_idx);

// -- Server
define("ERR_SERVER_InvalidRequestMethod", ++$const_idx);
define("ERR_SERVER_InvalidRequestFormat", ++$const_idx);

// -- SQL
define("ERR_SQL_ConnectionFailed", ++$const_idx);
define("ERR_SQL_QueryCreationFailed", ++$const_idx);
define("ERR_SQL_QueryExecutionFailed", ++$const_idx);

// -- Account
define("ERR_ACC_InvalidLogType", ++$const_idx);
define("ERR_ACC_UsernameNotUnique", ++$const_idx);

// -- Session
define("ERR_SES_InvalidFormat", ++$const_idx);
define("ERR_SES_InvalidSession", ++$const_idx);