<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Template_loader;
use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$not_found_error = new Template_loader(HTML_INCLUDES_PATH . "/http_error.php", array("status_code" => "403", "error_message" => "Απαγορεύεται η σύνδεση σε αυτή τη σελίδα!"));
#endregion

#region Print Body
    Head_header_manager::print_html_header_and_head();
    $navigation->get_body();
    $not_found_error->get_body();
    $footer->get_body();
#endregion