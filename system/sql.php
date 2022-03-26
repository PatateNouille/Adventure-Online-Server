<?php

// ------ INCLUDES

require('system.php');



// ------ SQL INITIALIZATION

$config = parse_ini_file('../config/config.ini');

// Create connection
$sql = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['database']);

// Check connection
if (!$sql) {
  log_error('Connection failed', mysqli_connect_error());
}



// ------ SQL HELPER METHODS

function sql_error($msg)
{
  global $sql;
  
  log_error($msg, mysqli_error($sql));
}

function query($query, $param_types = null, ...$params)
{
  global $sql;
  
  $stmt = mysqli_prepare($sql, $query);
  
  if ($stmt === false)
    sql_error('Failed to create query: '.$query);
  
  if ($param_types != null)
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
  
  if (mysqli_stmt_execute($stmt) === false)
    sql_error('Failed to execute query: '.$query);
  
  return mysqli_stmt_get_result($stmt);
}

?>