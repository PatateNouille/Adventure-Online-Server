<?php // ------ ERROR HANDLING

if ($_SERVER['REQUEST_METHOD'] != 'POST')
  die('Invalid request type');

?>


<?php

$content = $_POST['content'];

echo 'Server received : '.$content.' !';

?>