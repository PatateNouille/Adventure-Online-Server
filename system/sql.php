<?php

// ------ INCLUDES

require_once('constants.php');
require_once('system.php');



// ------ SQL INITIALIZATION

$config = parse_ini_file(DIR_ROOT.'config/config.ini');

// Create connection
$sql = mysqli_connect($config['server'], $config['username'], $config['password'], $config['database']);

// Check connection
if (!$sql) {
  log_error(ERR_SQL_ConnectionFailed, 'Connection failed', mysqli_connect_error());
}



// ------ SQL EXCEPTIONS

class SqlException extends Exception
{

}



// ------ SQL HELPER METHODS

function sql_error(int $code, string $msg): void
{
  global $sql;
  
  throw new SqlException(
    $msg.'\n'.mysqli_error($sql),
    $code
  );
}

function query(string $query, string $param_types = null, ...$params): mixed
{
  global $sql;
  
  $stmt = mysqli_prepare($sql, $query);
  
  if ($stmt === false)
    sql_error(ERR_SQL_QueryCreationFailed, 'Failed to create query: '.$query);
  
  if ($param_types != null)
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
  
  if (mysqli_stmt_execute($stmt) === false)
    sql_error(ERR_SQL_QueryExecutionFailed, 'Failed to execute query: '.$query);
  
  return mysqli_stmt_get_result($stmt);
}

?>