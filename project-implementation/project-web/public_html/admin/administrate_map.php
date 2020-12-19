<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(REQUIRES_PATH . "/protect_admin_only_page.php");

#region Uses
use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Navigation;
use project_web\resources\components\Footer;
use project_web\resources\components\Map;
#endregion

$map_settings = array(
    "show_polygons" => "true",
    "polygons_url" => "/api/polygons_coordinates.php",
    "show_polygons_popup" => "true",
    "pop_up_url" => "/admin/pop_up.php",
    "pop_up_info" => array(
        "pop_up_available_places_input_id" => "pop_up_available_places_input",
        "pop_up_demand_curve_selector_id" => "pop_up_demand_curve_selector",
        "pop_up_submit_button_id" => "pop_up_submit",
    ),
    "polygon_parking_info_url" => "/admin/api/polygon_parking_info.php",
);
$top_spaces = array(
    "0" => "56",
);
$bottom_spaces = array(
    "0" => "33",
);

#region Component instantiation
Head_header_manager::add_script_to_head(CONFIG["paths"]["js"] . "/pop_up.js");
$navigation = new Navigation;
$footer = new Footer;
$map = new Map($map_settings, $top_spaces, $bottom_spaces);
#endregion

#region Print Body
Head_header_manager::print_html_header_and_head();
$navigation->get_body();
$map->get_body();
$footer->get_body();
#endregion
