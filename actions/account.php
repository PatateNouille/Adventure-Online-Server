<?php

// ------ INCLUDES

require_once('../system/constants.php');
require_once('../system/system.php');
require_once('../system/sql.php');



// ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  log_error(
    ERR_SERVER_InvalidRequestMethod,
    'Invalid request type', 'REQUEST_METHOD != POST');



// ------ TEST SCRIPT

$json = file_get_contents('php://input');

$data = json_decode($json);

if (!isset($data->username)
 || !isset($data->password)
 || !isset($data->log_type))
  log_error(
    ERR_SERVER_InvalidRequestFormat,
    'Invalid request format', 'Expected credentials and login type');

$output = array();

$name = htmlspecialchars($data->username);
$pswd = htmlspecialchars($data->password);

switch ($data->log_type)
{
  case 'REGISTER':
    {
      $result = query(
        'SELECT id FROM accounts WHERE username=?',
        's',
        $name
      );

      if ($result->num_rows != 0)
        log_error(
          ERR_ACC_UsernameNotUnique,
          'Username not unique', 'An account with that username already exists');

      $hash = password_hash($pswd, PASSWORD_DEFAULT);
      
      $result = query(
        'INSERT INTO accounts(username, password) VALUES (?, ?)',
        'ss',
        $name, $hash);
    }
    
  case 'LOGIN':
    {
      $result = query(
        'SELECT id,password FROM accounts WHERE username=?',
        's',
        $name);
      
      $obj = $result->fetch_object();
      
      $output['id_account'] = password_verify($pswd, $obj->password) ? $obj->id : -1;
    }
    break;
    
  default:
    log_error(
      ERR_ACC_InvalidLogType,
      'Invalid log type', 'Expected REGISTER or LOGIN');
    break;
}

print(json_encode($output));

?>