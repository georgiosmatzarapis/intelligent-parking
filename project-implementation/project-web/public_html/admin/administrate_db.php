<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(REQUIRES_PATH . "/protect_admin_only_page.php");

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\File_upload_form;
use project_web\resources\components\Clear_database;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$upload_form = new File_upload_form;
$clear_database = new Clear_database;
#endregion

#region Print Body
Head_header_manager::print_html_header_and_head();
$navigation->get_body();
$upload_form->get_body();
$clear_database->get_body();
$footer->get_body();
#endregion
