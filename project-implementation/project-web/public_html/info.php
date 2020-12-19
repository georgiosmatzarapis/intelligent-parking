<?php
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

if(DEBUG)
    phpinfo();
else
    http_response_code(404);
?>