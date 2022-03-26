<?php

// ------ INCLUDES

require('system/sql.php');

// ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  log_error('Invalid request type', 'REQUEST_METHOD != POST');



// ------ TEST SCRIPT

$json = file_get_contents('php://input');

$data = json_decode($json);

if (!isset($data->username)
 || !isset($data->password)
 || !isset($data->log_type))
  log_error('Invalid request format', 'Expected credentials and login type');

$output = array();

$name = htmlspecialchars($data->username);
$pswd = password_hash($data->password);

switch ($data->log_type)
{
  case 'REGISTER':
    {
      $hash = password_hash($pswd);

      $result = query(
        'INSERT INTO accounts(username, password) VALUES (?, ?)',
        'ss',
        $name, $hash);
      
      if ($result === false)
        log_error('Username already exists', 'An account with that username already exists');
    }
    
  case 'LOGIN':
    {
      $result = query(
        'SELECT id,password FROM accounts WHERE username=?',
        's',
        $name);
      
      $obj = $result->fetch_object();
      
      $output['account'] = password_verify($pswd, $obj->password) ? $obj->id : -1;
    }
    break;
    
  default:
    log_error('Invalid log type', 'Expected REGISTER or LOGIN');
    break;
}

print(json_encode($output));

?>