<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;
#endregion

#region Component instantiation
#endregion

#region Print Body
// Head_header_manager::print_html_header_and_head();
Head_header_manager::print_json_header();

echo '[{"x":"40.643012616714856", "y":"22.93400457702626"}]';

#endregion
