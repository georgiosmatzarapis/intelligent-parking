<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\Login;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$login_form = new Login;
#endregion

#region Print Body
Head_header_manager::print_html_header_and_head();
$navigation->get_body();
$login_form->get_body();
$footer->get_body();
#endregion
