<?php

// ------ ERROR CODES

$const_idx = -1;

// -- Global
define('ERR', 'ERR_');
define(ERR.'Exception', ++$const_idx);

// -- Server
define('SERVER', 'SERVER_');
define(ERR.SERVER.'InvalidRequestMethod', ++$const_idx);
define(ERR.SERVER.'InvalidRequestFormat', ++$const_idx);

// -- SQL
define('SQL', 'SQL_');
define(ERR.SQL.'ConnectionFailed', ++$const_idx);
define(ERR.SQL.'QueryCreationFailed', ++$const_idx);
define(ERR.SQL.'QueryExecutionFailed', ++$const_idx);

// -- Account
define('ACC', 'ACC_');
define(ERR.ACC.'InvalidLogType', ++$const_idx);
define(ERR.ACC.'UsernameNotUnique', ++$const_idx);



// ------ PATHS

define('DIR_ROOT', '/home/u247713460/domains/patatenouille.link/public_html/adventure-online/');
?>