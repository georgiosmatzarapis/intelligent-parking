<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

require_once(LIBRARY_PATH . "/third_party/phayes-geoPHP/geoPHP.inc");
require_once(LIBRARY_PATH . "/third_party/dbscan.php");

require_once(CLASSES_PATH . '/Polygon.php');

#region Uses
use project_web\resources\library\classes\Head_header_manager;
use project_web\resources\library\classes\Polygon;
#endregion

#region Component instantiation
#endregion

#region Print Body
Head_header_manager::print_json_header();

function distance($geoPHP_point1, $geoPHP_point2)
{
    $x1 = $geoPHP_point1->getX();
    $y1 = $geoPHP_point1->getY();
    $x2 = $geoPHP_point2->getX();
    $y2 = $geoPHP_point2->getY();
    return sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
}

function float_random($from, $to)
{
    $multiply = 10000000000000000;
    return mt_rand((int) ceil($from * $multiply), (int) ceil($to * $multiply)) / $multiply;
}

function run_dbscan($geoPHP_points)
{
    //Generate dictionary with <key>:<value> = id:goePHP_point
    $point_ids = array();
    $id_geoPHP_point_dict = array();
    $id = 0;
    foreach ($geoPHP_points as $geoPHP_point) {
        $id_geoPHP_point_dict["$id"] = $geoPHP_point;
        array_push($point_ids, $id);
        $id++;
    }

    //Generate distance matrix
    $distance_matrix = array();
    foreach ($point_ids as $id1) {
        $distance_matrix["$id1"] = array();
        foreach ($point_ids as $id2) {
            $distance_matrix["$id1"]["$id2"] = distance($id_geoPHP_point_dict["$id1"], $id_geoPHP_point_dict["$id2"]);
        }
    }

    //Run dbscan
    $DBSCAN = new \DBSCAN($distance_matrix, $point_ids);

    $epsilon = 0.0001;
    $minpoints = 2;
    $clusters = $DBSCAN->dbscan($epsilon, $minpoints);

    //Find max clusters
    $max_cluster_ids = array();
    $max_cluster_size = 0;
    foreach ($clusters as $index => $cluster) {
        if (sizeof($cluster) > $max_cluster_size) {
            $max_cluster_size = sizeof($cluster);
            $max_cluster_ids = array($index);
        } else if (sizeof($cluster) == $max_cluster_size) {
            array_push($max_cluster_ids, $index);
        }
    }

    //Find max clusters centroids
    $geoPHP_points_to_ret = array();
    foreach ($max_cluster_ids as $cluster_id) {
        $geoPHP_points = array();
        foreach ($clusters["$cluster_id"] as $point_id) {
            array_push($geoPHP_points, $id_geoPHP_point_dict["$point_id"]);
        }

        $wkt_multipoint = "MULTIPOINT (";
        foreach ($geoPHP_points as $geoPHP_point) {
            $wkt_multipoint .= $geoPHP_point->getX() . " " . $geoPHP_point->getY();
            $wkt_multipoint .= ", ";
        }
        $wkt_multipoint = substr($wkt_multipoint, 0, -2);
        $wkt_multipoint .= ")";

        $geoPHP_multipoint = \geoPHP::load($wkt_multipoint, 'wkt');

        array_push($geoPHP_points_to_ret, $geoPHP_multipoint->getCentroid());
    }

    return $geoPHP_points_to_ret;
}

$resp = array("success" => "false");
if (isset($_GET["unixtime"]) && isset($_GET["max_distance"]) && isset($_GET["x"]) && isset($_GET["y"])) {
    $unixtime = htmlspecialchars($_GET["unixtime"]);
    $max_distance = htmlspecialchars($_GET["max_distance"]);
    $x = htmlspecialchars($_GET["x"]);
    $y = htmlspecialchars($_GET["y"]);

    $hours = $unixtime / 3600 % 24;
    $minutes = $unixtime / 60 % 60;
    $geoPHP_point = \geoPHP::load("POINT($x $y)", 'wkt');

    $polygon_array = Polygon::create_polygons_array_from_select_arround_point($geoPHP_point, $max_distance);

    $all_geoPHP_points = array();
    foreach ($polygon_array as $polygon) {
        $id = $polygon->id;
        $radius_degrees = 50 / (111.32 * 1000 * cos($polygon->geoPHP_centroid->getY() * (pi() / 180)));
        $available_spots = $polygon->parking_spots - $polygon->get_taken_spots_number($hours, $minutes);

        for ($i = 0; $i < $available_spots; $i++) {
            $random_point_x_no_offset = float_random(-$radius_degrees, $radius_degrees);
            $max_y = sqrt(pow($radius_degrees, 2) - pow($random_point_x_no_offset, 2));

            $random_point_x = $random_point_x_no_offset + $polygon->geoPHP_centroid->getX();
            $random_point_y = float_random(-$max_y, $max_y) + $polygon->geoPHP_centroid->getY();

            $random_wkt_point = "POINT($random_point_x $random_point_y)";
            $random_geoPHP_point = \geoPHP::load($random_wkt_point, 'wkt');
            array_push($all_geoPHP_points, $random_geoPHP_point);
        }
    }

    $geoPHP_point_to_ret = run_dbscan($all_geoPHP_points);

    $resp = array(
        "success" => "true",
        "spots" => array()
    );

    foreach ($geoPHP_point_to_ret as $spot) {
        $spot_x = $spot->getX();
        $spot_y = $spot->getY();
        $distance = distance($spot, $geoPHP_point);

        array_push($resp["spots"], array(
            "x" => $spot_x,
            "y" => $spot_y,
            "distance" => $distance,
        ));
    }
}

echo json_encode($resp);
#endregion
