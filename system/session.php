<?php

// ------ INCLUDES

require_once("constants.php");
require_once("system.php");
require_once("sql.php");
require_once("action.php");



// ------ SESSION HELPER METHODS

function assert_session_valid(Session $session): void
{
  if ($session->is_valid()) return;

  log_error(
    ERR_SES_InvalidSession,
    "Invalid session", "The session provided is invalid, you may need to log in again"
  );
}



// ------ SESSION CLASS

class Session
{
  protected $id;
  protected $token;



  public function __construct(int $id, string $token)
  {
    $this->id = $id;
    $this->token = $token;
  }

  public static function make_from_fields(array $data): Session
  {
    return Session::make_from_action(new ActionInput($data));
  }

  public static function make_from_action(ActionInput $input): Session
  {
    assert_action_fields($input, [
      "id",
      "token"
    ], "Session info has invalid format", ERR_SES_InvalidFormat);

    return new Session(
      $input["id"],
      $input["token"]
    );
  }



  public static function open(int $account_id): Session
  {
    $result = query(
      "SELECT id FROM session WHERE id_account = ?",
      "i",
      $account_id
    );

    while ($session = $result->fetch_object())
    {
      Session::internal_close($session->id);
    }

    $result = query(
      "SELECT open_session(?, ?) AS id",
      "ii",
      $account_id,
      SESSION_Duration
    );

    $id = $result->fetch_object()->id;

    $result = query(
      "SELECT account.username, session.expiration
       FROM session, account
       WHERE session.id = ?
       AND session.id_account = account.id",
      "i",
      $id
    );

    $info = $result->fetch_object();
    $exp_date = Session::get_exp_date($info->expiration);

    $token = Session::hash_token($id, $info->username, $exp_date);

    return new Session($id, $token);
  }

  public function close(): bool
  {
    global $sql;

    if (!$this->exists())
      return false;

    Session::internal_close($this->id);

    return mysqli_errno($sql) == 0;
  }



  public function exists(): bool
  {
    $result = query(
      "SELECT id FROM session WHERE id = ?",
      "i",
      $this->id
    );

    return Session::check_exists($result);
  }

  public function get_time_left(): int
  {
    $result = query(
      "SELECT expiration FROM session WHERE id = ?",
      "i",
      $this->id
    );

    if (!Session::check_exists($result))
      return -1;
    
    $info = $result->fetch_object();
    $exp_date = Session::get_exp_date($info->expiration);
    
    return Session::get_exp_time($exp_date);
  }

  public function is_valid(): bool
  {
    $result = query(
      "SELECT session.id, account.username, session.expiration
       FROM session, account
       WHERE session.id = ?
       AND session.id_account = account.id",
      "i",
      $this->id
    );

    if (!Session::check_exists($result))
      return false;

    $info = $result->fetch_object();
    $exp_date = Session::get_exp_date($info->expiration);
    
    if (Session::get_exp_time($exp_date) < 0)
      return false;

    return Session::verify_token($info->id, $info->username, $exp_date, $this->token);
  }



  public function get_id(): int
  {
    return $this->id;
  }

  public function get_token(): string
  {
    return $this->token;
  }



  protected static function check_exists(mysqli_result $result): bool
  {
    return $result->num_rows != 0;
  }

  protected static function get_exp_date(string $sql_date): DateTime
  {
    return DateTime::createFromFormat("Y-m-d H:i:s", $sql_date);
  }

  protected static function get_exp_time(DateTime $exp_date): int
  {
    return $exp_date->getTimestamp() - time();
  }



  protected static function internal_close(int $session_id)
  {
    query(
      "DELETE FROM session WHERE id = ?",
      "i",
      $session_id
    );
  }

  protected static function format_token(int $session_id, string $username, DateTime $expiration): string
  {
    return $session_id."&".$username."&".$expiration->format("Y-m-d H:i:s");
  }

  protected static function verify_token(int $session_id, string $username, DateTime $expiration, string $token): bool
  {
    $str = Session::format_token($session_id, $username, $expiration);

    return password_verify($str, $token);
  }

  protected static function hash_token(int $session_id, string $username, DateTime $expiration): string
  {
    $str = Session::format_token($session_id, $username, $expiration);

    return password_hash($str, PASSWORD_BCRYPT);
  }
}