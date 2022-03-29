<?php

// ------ INCLUDES

require_once('../system/constants.php');
require_once('../system/system.php');
require_once('../system/sql.php');
require_once('../system/action.php');



// ------ ERROR HANDLING

assert_request_method();



// ------ TEST SCRIPT

$input = new ActionInput();
$output = new ActionOutput();

try
{
  $input->assert_fields([
    'log_type',
    'username',
    'password'
  ]);
}
catch (ArgumentCountError $e)
{
  log_error(
    ERR_SERVER_InvalidRequestFormat,
    'Request has invalid format',
    $e->getMessage()
  );
}

$name = htmlspecialchars($input->get_field('username'));
$pswd = htmlspecialchars($input->get_field('password'));

switch ($input->get_field('log_type'))
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
      
      $output->set_field('id_account', password_verify($pswd, $obj->password) ? $obj->id : -1);
    }
    break;
    
  default:
    log_error(
      ERR_ACC_InvalidLogType,
      'Invalid log type', 'Expected REGISTER or LOGIN');
    break;
}

echo $output->to_string();

?>