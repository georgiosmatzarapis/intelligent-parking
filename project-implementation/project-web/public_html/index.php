<?php
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Template_loader;
use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$instructions = new Template_loader(HTML_INCLUDES_PATH . "/instructions.php", array());
#endregion

#region Print Body
    Head_header_manager::print_html_header_and_head();
    $navigation->get_body();
    $instructions->get_body();
    $footer->get_body();
#endregion