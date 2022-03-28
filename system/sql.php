<?php

// ------ INCLUDES

require('system.php');



// ------ SQL INITIALIZATION

$config = parse_ini_file('../config/config.ini');

// Create connection
$sql = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['database']);

// Check connection
if (!$sql) {
  log_error(Error_Code::SQL_ConnectionFailed, 'Connection failed', mysqli_connect_error());
}



// ------ SQL HELPER METHODS

function sql_error(Error_Code $code, string $msg): void
{
  global $sql;
  
  log_error($code, $msg, mysqli_error($sql));
}

function query(string $query, Error_Code $err_creation, Error_Code $err_execution, string $param_types = null, mixed ...$params): mixed
{
  global $sql;
  
  $stmt = mysqli_prepare($sql, $query);
  
  if ($stmt === false)
    sql_error($err_creation, 'Failed to create query: '.$query);
  
  if ($param_types != null)
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
  
  if (mysqli_stmt_execute($stmt) === false)
    sql_error($err_execution, 'Failed to execute query: '.$query);
  
  return mysqli_stmt_get_result($stmt);
}

function query(string $query, string $param_types = null, mixed ...$params): mixed
{
  return query(
    $query,
    Error_Code::SQL_QueryCreationFailed,
    Error_Code::SQL_QueryExecutionFailed,
    $param_types,
    ...$params);
}

?>