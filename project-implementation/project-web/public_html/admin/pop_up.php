<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

#region Uses
// use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\on_top\Map_popup;
#endregion

#region Component instantiation
$popup = new Map_popup;
#endregion

#region Print Body
// Head_header_manager::print_html_header_and_head();
$popup->get_body();
#endregion
