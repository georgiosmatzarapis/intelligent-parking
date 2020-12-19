<?php
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\About_us;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$about_us = new About_us;
#endregion

#region Print Body
    Head_header_manager::print_html_header_and_head();
    $navigation->get_body();
    $about_us->get_body();
    $footer->get_body();
#endregion