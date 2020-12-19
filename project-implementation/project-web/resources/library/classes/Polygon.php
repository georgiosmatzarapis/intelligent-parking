<?php

namespace project_web\resources\library\classes;

require_once(realpath(dirname(__FILE__) . "/../../config.php"));

require_once(LIBRARY_PATH . "/third_party/phayes-geoPHP/geoPHP.inc");

require_once(CLASSES_PATH . '/Database_manager.php');

use project_web\resources\library\classes\Database_manager;

class Polygon
{
    public $id;
    public $population;
    public $parking_spots;
    public $demand_curve_id;
    public $geoPHP_polygon; //Stored as geoPHP geometry
    public $geoPHP_centroid; //Stored as geoPHP gemometry

    private static $demand_curves;
    private static $demand_curves_values;

    private $polygon_for_insert = false;
    private $polygon_for_update = false;

    private static function get_demand_curves_from_db()
    {
        Polygon::$demand_curves = array();

        $select = "SELECT DISTINCT id, label FROM Demand_Curve";
        Database_manager::execute_select($select);

        $results = Database_manager::get_last_select_results();

        foreach ($results as $row) {
            $id = $row["id"] ?: -1;
            $label = $row["label"] ?: "Μη Ορισμένο";
            Polygon::$demand_curves["$id"] = $label;
        }
    }

    private static function get_demand_curves_values_from_db()
    {
        if (isset(Polygon::$demand_curves_values))
            return Polygon::$demand_curves_values;

        Polygon::$demand_curves_values = array();

        $select = "SELECT id, hour, minute, probability FROM Demand_Curve";
        Database_manager::execute_select($select);

        $results = Database_manager::get_last_select_results();

        foreach ($results as $row) {
            $id = $row["id"];
            $hour = $row["hour"];
            $minute = $row["minute"];
            $probability = $row["probability"];

            if (!isset(Polygon::$demand_curves_values["$id"])) {
                Polygon::$demand_curves_values["$id"] = array("$hour" => array("$minute" => "$probability"));
            } else {
                Polygon::$demand_curves_values["$id"]["$hour"] = array("$minute" => "$probability");
            }
        }
        return Polygon::$demand_curves_values;
    }

    public static function create_polygon_for_insert($id, $population, $linear_ring_coordinates)
    {
        $to_ret = new Polygon();
        $to_ret->polygon_for_insert = true;
        $to_ret->id = $id;
        $to_ret->population = $population;

        //We want to go from this: "A.AA,a.aa B.BB,b.bb C.CC,c.cc " to this: "POLYGON((a.aa A.AA,b.bb B.BB,c.cc C.CC))"
        $linear_ring_coordinates = str_replace(" ", "|", $linear_ring_coordinates);
        $linear_ring_coordinates = str_replace(",", " ", $linear_ring_coordinates);
        $linear_ring_coordinates = str_replace("|", ",", $linear_ring_coordinates); //TODO change replaces with regex
        $linear_ring_coordinates = preg_replace('/(\d+.\d+)\s(\d+.\d+)/', '$2 $1', $linear_ring_coordinates);
        $linear_ring_coordinates = "POLYGON((" . $linear_ring_coordinates . "))";

        $to_ret->geoPHP_polygon = \geoPHP::load($linear_ring_coordinates, 'wkt');
        $to_ret->geoPHP_centroid = $to_ret->geoPHP_polygon->getCentroid();

        return $to_ret;
    }

    public static function create_polygon_from_update($id, $parking_spots, $demand_curve_id)
    {
        $to_ret = Polygon::create_polygon_from_select($id);
        $to_ret->polygon_for_update = true;
        $to_ret->parking_spots = $parking_spots;
        $to_ret->demand_curve_id = $demand_curve_id;

        Database_manager::add_update_statement_for_async_exec($to_ret->to_sql_update());
        Database_manager::execute_updates();

        //Reselect to make sure changes happend
        $to_ret = Polygon::create_polygon_from_select($id);

        return $to_ret;
    }

    public static function create_polygons_array_from_select_arround_point($geoPHP_point, $meters_distance)
    {
        $degrees_distance = $meters_distance / (111.32 * 1000 * cos($geoPHP_point->getY() * (pi() / 180)));
        $wkt_point = (new \WKT())->write($geoPHP_point);

        $to_ret = array();
        $select = "SELECT id, population, parking_spots, demand_curve_id, ST_AsText(polygon) as polygon, ST_AsText(centroid) as centroid FROM Polygon WHERE MBRcovers(Buffer(ST_PointFromText('$wkt_point'), $degrees_distance), Polygon.centroid) = 1";
        Database_manager::execute_select($select);

        $results = Database_manager::get_last_select_results();

        foreach ($results as $row) {
            $poly = new Polygon();
            $poly->id = $row["id"];
            $poly->population = $row["population"] ?: 0;
            $poly->parking_spots = $row["parking_spots"] ?: 0;
            $poly->demand_curve_id = $row["demand_curve_id"] ?: -1;
            $poly->geoPHP_polygon = \geoPHP::load($row["polygon"] ?: "", 'wkt');
            $poly->geoPHP_centroid = \geoPHP::load($row["centroid"] ?: "", 'wkt');

            array_push($to_ret, $poly);
        }
        return $to_ret;
    }

    public static function create_polygons_array_from_select()
    {
        $to_ret = array();
        $select = "SELECT id, population, parking_spots, demand_curve_id, ST_AsText(polygon) as polygon, ST_AsText(centroid) as centroid FROM Polygon";
        Database_manager::execute_select($select);

        $results = Database_manager::get_last_select_results();

        foreach ($results as $row) {
            $poly = new Polygon();
            $poly->id = $row["id"];
            $poly->population = $row["population"] ?: 0;
            $poly->parking_spots = $row["parking_spots"] ?: 0;
            $poly->demand_curve_id = $row["demand_curve_id"] ?: -1;
            $poly->geoPHP_polygon = \geoPHP::load($row["polygon"] ?: "", 'wkt');
            $poly->geoPHP_centroid = \geoPHP::load($row["centroid"] ?: "", 'wkt');

            array_push($to_ret, $poly);
        }
        return $to_ret;
    }

    public static function create_polygon_from_select($id)
    {
        $select = "SELECT id, population, parking_spots, demand_curve_id, ST_AsText(polygon) as polygon, ST_AsText(centroid) as centroid FROM Polygon WHERE id = $id";
        Database_manager::execute_select($select);

        $results = Database_manager::get_last_select_results();
        foreach ($results as $row) {
            $poly = new Polygon();
            $poly->id = $row["id"];
            $poly->population = $row["population"] ?: 0;
            $poly->parking_spots = $row["parking_spots"] ?: 0;
            $poly->demand_curve_id = $row["demand_curve_id"] ?: -1;
            $poly->geoPHP_polygon = \geoPHP::load($row["polygon"] ?: "", 'wkt');
            $poly->geoPHP_centroid = \geoPHP::load($row["centroid"] ?: "", 'wkt');
        }

        return $poly;
    }

    public function get_demand_curves()
    {
        if (!isset(Polygon::$demand_curves)) {
            Polygon::get_demand_curves_from_db();
        }

        $curves = array(); //TODO remove
        $curves = Polygon::$demand_curves;
        if ($this->demand_curve_id == -1) {
            $curves["-1"] = "Μη Ορισμένο";
        }
        return $curves;
    }

    public function get_taken_spots_number($hours, $minutes)
    {
        $probability_of_demand = Polygon::get_demand_curves_values_from_db()["$this->demand_curve_id"]["$hours"]["$minutes"] ?? Polygon::get_demand_curves_values_from_db()["$this->demand_curve_id"]["$hours"]["0"];
        $available_spots = $this->parking_spots;

        $reserved_spots = ceil($this->population * 0.2);
        $available_spots -= $reserved_spots;

        //...Assuming that db probabilities are taken into account only for non-reserved places
        $available_spots -= $available_spots * $probability_of_demand;

        if ($available_spots < 0) {
            $available_spots = 0;
        }

        return ceil($this->parking_spots - $available_spots);
    }

    public function get_taken_spots_percentage($hours, $minutes)
    {
        if ($this->parking_spots <= 0) {
            return 1;
        }

        return $this->get_taken_spots_number($hours, $minutes) / $this->parking_spots;
    }

    public function to_sql_insert()
    {
        if (!$this->polygon_for_insert)
            return "";

        $wkt_polygon = (new \WKT())->write($this->geoPHP_polygon);
        $wkt_centroid = (new \WKT())->write($this->geoPHP_centroid);

        $base =  "INSERT INTO Polygon (id, population, polygon, centroid) VALUES ";
        $values = "($this->id, $this->population, ST_PolygonFromText('$wkt_polygon'), ST_PointFromText('$wkt_centroid')";
        return $base . $values . ");";
    }

    public function to_sql_update()
    {
        if (!$this->polygon_for_update)
            return "";
        return "UPDATE Polygon SET parking_spots = $this->parking_spots, demand_curve_id = $this->demand_curve_id WHERE id = $this->id;";
    }

    public function get_geojson_phparray_polygon()
    {
        $geojson = (new \GeoJSON())->write($this->geoPHP_polygon);
        return json_decode($geojson, true)["coordinates"];
    }

    public function get_geojson_phparray_centroid()
    {
        $geojson = (new \GeoJSON())->write($this->geoPHP_centroid);
        return json_decode($geojson, true)["coordinates"];
    }
}
