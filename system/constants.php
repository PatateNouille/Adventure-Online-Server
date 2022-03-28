<?php

// ------ ERROR CODES

enum Error_Code
{
  case SERVER_InvalidRequestMethod;
  case SERVER_InvalidRequestFormat;

  case SQL_ConnectionFailed;
  case SQL_QueryCreationFailed;
  case SQL_QueryExecutionFailed;
  

  
  case ACC_InvalidLogType;
  case ACC_UsernameNotUnique;
}

?>