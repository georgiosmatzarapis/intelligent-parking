<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

require_once(LIBRARY_PATH . "/third_party/phayes-geoPHP/geoPHP.inc");

require_once(CLASSES_PATH . '/Polygon.php');

#region Uses
use project_web\resources\library\classes\Head_header_manager;
use project_web\resources\library\classes\Polygon;
#endregion

Head_header_manager::print_json_header();

$response = array();

$polygon_array = Polygon::create_polygons_array_from_select();
foreach ($polygon_array as $polygon) {
    $response["$polygon->id"] = $polygon->get_geojson_phparray_polygon();
}

echo json_encode($response);
