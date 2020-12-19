<?php

namespace project_web\resources\library\classes;

require_once(realpath(dirname(__FILE__) . "/../../config.php"));

class Database_manager
{ //TODO add database settings and convert from static
    private static $insert_statments = array();
    private static $update_statments = array();
    private static $select_results = array();

    public static function add_insert_statement_for_async_exec($statement)
    {
        array_push(Database_manager::$insert_statments, $statement);
    }

    public static function add_update_statement_for_async_exec($statement)
    {
        array_push(Database_manager::$update_statments, $statement);
    }

    private static function db_connection()
    {
        $conn = new \mysqli(CONFIG["db"]["host"], CONFIG["db"]["username"], CONFIG["db"]["password"], CONFIG["db"]["dbname"]);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (!$conn->set_charset("utf8")) {
            die("Cannot set charset to utf8");
        }

        return $conn;
    }

    public static function execute_inserts()
    {
        $conn = Database_manager::db_connection();

        // $temp = Database_manager::$insert_statments->getArrayCopy ();
        // Database_manager::$insert_statments = array();

        foreach (Database_manager::$insert_statments as $q) {
            $conn->multi_query($q);
            while ($conn->more_results()) {
                $conn->next_result();
            }
        }
        Database_manager::$insert_statments = array();
        $conn->close();
    }

    public static function execute_updates()
    {
        $conn = Database_manager::db_connection();

        // $temp = Database_manager::$insert_statments;
        // Database_manager::$insert_statments = array();

        foreach (Database_manager::$update_statments as $q) {
            $conn->multi_query($q);
            while ($conn->more_results()) {
                $conn->next_result();
            }
        }
        Database_manager::$update_statments = array();
        $conn->close();
    }

    public static function clean_db()
    {
        $conn = Database_manager::db_connection();
        $conn->query("DELETE FROM `Polygon`;");
        while ($conn->more_results()) {
            $conn->next_result();
        }
        $conn->close();
    }

    public static function show_inserts()
    {
        print_r(Database_manager::$insert_statments);
    }

    public static function show_updates()
    {
        print_r(Database_manager::$update_statments);
    }

    public static function execute_select($statement)
    {
        //First clean results array
        Database_manager::$select_results = array();

        //Now add results to array
        $conn = Database_manager::db_connection();
        $result = $conn->query($statement);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push(Database_manager::$select_results, $row);
            }
        }
        $conn->close();

        // print_r(Database_manager::$select_results);
    }

    public static function get_last_select_results()
    {
        return Database_manager::$select_results;
    }
}
