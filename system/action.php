<?php

// ------ INCLUDES

require_once("constants.php");
require_once("system.php");



// ------ ERROR HANDLING METHODS

function assert_request_method()
{
  if ($_SERVER["REQUEST_METHOD"] != "POST")
    log_error(
      ERR_SERVER_InvalidRequestMethod,
      "Invalid request type", "REQUEST_METHOD != POST");
}

function assert_action_fields(ActionInput $input, array $fields, string $err_msg, int $err_code = ERR_SERVER_InvalidRequestFormat)
{
  foreach ($fields as $field)
  {
    $subfields = explode(".", $field);

    $exists = true;
    $last = $input;
    foreach ($subfields as $subfield)
    {
      if (!isset($last[$subfield]))
      {
        $exists = false;
        break;
      }

      $last = $last[$subfield];
    }
    
    if ($exists) continue;

    log_error(
      $err_code,
      $err_msg,
      "Missing field '".$field."' !"
    );
  }
}



// ------ ACTION HELPER CLASSES

class ActionInput implements ArrayAccess
{
  protected $data;


  
  public static function make_from_post_body(): ActionInput
  {
    $json = file_get_contents("php://input");
    
    return new ActionInput(json_decode($json, true));
  }

  public function __construct(array $data)
  {
    $this->data = array();

    foreach ($data as $key => $field)
    {
      $this->data[$key] = is_array($field)
        ? new ActionInput($field)
        : $field;
    }
  }



  public function offsetGet($name)
  {
    if (!isset($this->data[$name]))
      return null;

    return $this->data[$name];
  }

  public function offsetSet($name, $value): void
  {
    throw new Exception("Cannot set ActionInput field '".$name."'");
  }



  public function offsetExists($name): bool
  {
    return isset($this->data[$name]);
  }

  public function offsetUnset($name): void
  {
    throw new Exception("Cannot unset ActionInput field '".$name."'");
  }
}



class ActionOutput implements ArrayAccess
{
  protected $data = array();





  public function offsetGet($name)
  {
    if (!isset($this->data[$name]))
      return null;

    return $this->data[$name];
  }

  public function offsetSet($name, $value): void
  {
    $this->data[$name] = $value;
  }



  public function offsetExists($name): bool
  {
    return isset($this->data[$name]);
  }

  public function offsetUnset($name): void
  {
    unset($this->data[$name]);
  }



  public function json(): string
  {
    return json_encode($this->data);
  }
}