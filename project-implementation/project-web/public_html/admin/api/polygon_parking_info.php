<?php
require_once(realpath(dirname(__FILE__) . "/../../../resources/config.php"));
require_once(REQUIRES_PATH . "/protect_admin_only_page.php");

require_once(LIBRARY_PATH . "/third_party/phayes-geoPHP/geoPHP.inc");

require_once(CLASSES_PATH . '/Polygon.php');

#region Uses
use project_web\resources\library\classes\Head_header_manager;
use project_web\resources\library\classes\Polygon;
#endregion

#region Component instantiation
function handle_get()
{
    if (!isset($_GET["id"])) {
        return array("success" => "false");
    }
    $id = htmlspecialchars($_GET["id"]);

    $polygon = Polygon::create_polygon_from_select($id);
    
    return array(
        "success" => "true",
        "id" => $polygon->id,
        "available_spots" => $polygon->parking_spots,
        "curve" => $polygon->demand_curve_id,
        "curves" => $polygon->get_demand_curves(),
    );

}

function handle_post()
{
    if (!isset($_POST["id"])) {
        return array("success" => "false");
    }
    $id = htmlspecialchars($_POST["id"]);

    if (!isset($_POST["available_spots"])) {
        return array("success" => "false");
    }
    $available_spots = htmlspecialchars($_POST["available_spots"]);

    if (!isset($_POST["curve"])) {
        return array("success" => "false");
    }
    $curve = htmlspecialchars($_POST["curve"]);

    $polygon = Polygon::create_polygon_from_update($id, $available_spots, $curve);
    
    return array(
        "success" => "true",
        "id" => $polygon->id,
        "available_spots" => $polygon->parking_spots,
        "curve" => $polygon->demand_curve_id,
        "curves" => $polygon->get_demand_curves(),
    );
}
#endregion

#region Print Body
Head_header_manager::print_json_header();

$response;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = handle_get();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = handle_post();
}

echo json_encode($response);
#endregion
