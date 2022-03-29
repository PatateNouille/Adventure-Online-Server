<?php

// ------ INCLUDES

require_once('constants.php');
require_once('system.php');



// ------ ERROR HANDLING METHODS

function assert_request_method()
{
  if ($_SERVER['REQUEST_METHOD'] != 'POST')
    log_error(
      ERR_SERVER_InvalidRequestMethod,
      'Invalid request type', 'REQUEST_METHOD != POST');
}



// ------ ACTION HELPER CLASSES

class ActionInput
{
  protected $data;


  
  public function __construct()
  {
    $json = file_get_contents('php://input');
    
    $this->data = json_decode($json, true);
  }



  public function assert_fields(array $fields)
  {
    foreach ($fields as $field)
    {
      if (isset($this->data[$field]))
        continue;
      
      throw new ArgumentCountError('Missing field "'.$field.'" !', ERR_SERVER_InvalidRequestFormat);
    }
  }

  public function get_field(string $name)
  {
    if (!isset($this->data[$name]))
      return null;

    return $this->data[$name];
  }
}



class ActionOutput
{
  protected $output = array();



  public function set_field(string $name, $value): bool
  {
    $existed = isset($this->output[$name]);
    $this->output[$name] = $value;

    return $existed;
  }

  public function to_string(): string
  {
    return json_encode($this->output);
  }
}

?>