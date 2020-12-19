<?php
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\Time_distance_form;
use project_web\resources\components\Map;
#endregion

#region Component instantiation
$navigation = new Navigation;
$footer = new Footer;
$form = new Time_distance_form;
$map_settings = array(
    // "show_polygons" => "true",
    // "polygons_url" => "/api/polygons_coordinates.php",
    // "color_polygons" => "true",
    // "polygons_availabilities_url" => "/api/polygons_availabilities.php",


    "enable_find_parking_spot" => "true",
    "default_marker_location" => "/api/default_pin_location.php", //TODO fix location
    "parking_spot_url" => "/api/parking_spot.php",
    "find_parking_spot_info" => $form->get_js_info(),
);
$top_spaces = array(
    "0" => "156",
    "576" => "110",
);
$bottom_spaces = array(
    "0" => "33",
);
$map = new Map($map_settings, $top_spaces, $bottom_spaces);
#endregion

#region Print Body
    Head_header_manager::print_html_header_and_head();
    $navigation->get_body();
    $form->get_body();
    $map->get_body();
    $footer->get_body();
#endregion