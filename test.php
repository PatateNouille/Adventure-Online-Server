<?php // ------ ERROR HANDLING

if (!isset($_POST['content']))
  die('Invalid request');

?>


<?php

$content = $_POST['content'];

echo 'Server received : '.$content.' !';

?>