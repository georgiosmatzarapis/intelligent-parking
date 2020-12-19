<?php
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\Timepick_timesteps_form;
use project_web\resources\components\Map;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$map_settings = array(
    "show_polygons" => "true",
    "polygons_url" => "/api/polygons_coordinates.php",
    "color_polygons" => "true",
    "polygons_availabilities_url" => "/api/polygons_availabilities.php",
    "recolor_polygons_event_name" => "recolor_polygons",
);
$top_spaces = array(
    "0" => "110",
);
$admin_top_spaces = array(
    "0" => "156",
    "576" => "110",
);
$bottom_spaces = array(
    "0" => "33",
);
$map = new Map($map_settings, (isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]) ? $admin_top_spaces : $top_spaces, $bottom_spaces);
$form = new Timepick_timesteps_form($map, isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]);
#endregion

#region Print Body
    Head_header_manager::print_html_header_and_head();
    $navigation->get_body();
    $form->get_body();
    $map->get_body();
    $footer->get_body();
#endregion