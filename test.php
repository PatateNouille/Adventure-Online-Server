<?php // ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  die('Invalid request type');

if (!isset($_POST['content']))
  die('Invalid request format');

?>


<?php

$content = $_POST['content'];

echo 'Server received : '.$content.' !';

?>