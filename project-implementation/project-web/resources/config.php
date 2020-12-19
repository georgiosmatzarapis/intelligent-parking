<?php

namespace project_web\resources;

defined("CONFIG")
    or define("CONFIG", array(
        "site_title" => "Ευφυές Σύστημα Διαχείρισης Στάθμευσης",
        "short_site_title" => "Ε&nbspΣ&nbspΔ&nbspΣ",
        "db" => array(
            "dbname" => "project-web",
            "username" => "root", //Using root is bad but it is easier for now.
            "password" => "rootpass", //Especially with this password...
            "host" => "localhost"
        ),
        "ext_urls" => array(
            "facebook" => "http://www.facebook.com",
            "null_island" => "https://en.wikipedia.org/wiki/Null_Island",
            "kamate_url" => "https://www.youtube.com/watch?v=6n3pFFPSlW4",
        ),
        "paths" => array(
            "resources" => realpath(dirname(__FILE__)),
            "img" => array(
                "content" => "/resources/img/content",
                "layout" => "/resources/img/layout"
            ),
            "css" => "/resources/css", //$_SERVER["DOCUMENT_ROOT"] . "/css"
            "js" => "/resources/js",
            "upload" => realpath(dirname(__FILE__)) . "/user_uploads",
        ),
        "navigation_pages_paths" => array(
            "index" => "/index.php",
            "about" => "/about.php",
            "parking_spot_availability" => "/parking_spot_availability.php",
            "find_parking_spot" => "/find_parking_spot.php",
            "admin_simulation" => "/parking_spot_availability.php",
            "admin_administrate_db" => "/admin/administrate_db.php",
            "admin_administrate_map" => "/admin/administrate_map.php",
            "admin_logout" => "/admin/login.php",
        ),
        "pages" => array(
            "index" => "/index.php",
            "about" => "/about.php",
            "parking_spot_availability" => "/parking_spot_availability.php",
            "find_parking" => "/find_parking.php",
            "find_parking_results" => "/find_parking_results.php",
            "load_kml" => "/admin/load_kml.php",
            "load_kml_execute" => "/admin/load_kml_execute.php",
            "configure_data" => "/admin/configure_data.php",
            "delete_db" => "/admin/delete_db.php",
            "logout" => "/admin/logout.php",
            "login" => "/admin/login.php",
            "login_execute" => "/admin/login_execute.php",
        ),
    ));

defined("COMMON_STYLESHEETS")
    or define("COMMON_STYLESHEETS", array(
        "bootstrap" => "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/css/bootstrap.min.css",
        "font-awesome" => "https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css",
        "main" => CONFIG["paths"]["css"] . "/main.css",
    ));

defined("COMMON_SCRIPTS")
    or define("COMMON_SCRIPTS", array(
        "jquery" => "https://code.jquery.com/jquery-3.3.1.slim.min.js",
        "popper" => "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js",
        "bootstrap" => "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js",
        "main" => CONFIG["paths"]["js"] . '/main.js',
    ));

defined("COMMON_DEFERED_SCRIPTS")
    or define("COMMON_DEFERED_SCRIPTS", array(
        "main_defer" => CONFIG["paths"]["js"] . '/main_defer.js',
    ));

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
 */
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("CLASSES_PATH")
    or define("CLASSES_PATH", LIBRARY_PATH . '/classes');

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("SNIPPETS_PATH")
    or define("SNIPPETS_PATH", realpath(dirname(__FILE__) . '/snippets'));

defined("COMPONENTS_PATH")
    or define("COMPONENTS_PATH", realpath(dirname(__FILE__) . '/components'));

defined("HTML_INCLUDES_PATH")
    or define("HTML_INCLUDES_PATH", realpath(dirname(__FILE__) . '/html_includes'));

defined("REQUIRES_PATH")
    or define("REQUIRES_PATH", realpath(dirname(__FILE__) . '/requires'));

defined("JS_INCLUDES_PATH")
        or define("JS_INCLUDES_PATH", realpath(dirname(__FILE__) . '/js_includes'));
    
// foreach (glob(COMPONENTS_PATH . "/*.php")  as $filename) {
//     require_once $filename;
// }



// /*
//     Create array with components you want to use. COMPONENT_CLASS => COMPONENT_PATH
//  */

defined("COMPONENTS")
    or define("COMPONENTS", array(
        "Time_distance_form" => COMPONENTS_PATH . "/Time_distance_form.php",
        "Navigation" => COMPONENTS_PATH . "/Navigation.php",
        "Map" => COMPONENTS_PATH . "/Map.php",
        "Timepick_timesteps_form" => COMPONENTS_PATH . "/Timepick_timesteps_form.php",
        "on_top\Map_popup" => COMPONENTS_PATH . "/on_top/Map_popup.php",
        "File_upload_form" => COMPONENTS_PATH . "/File_upload_form.php",
        "Login" => COMPONENTS_PATH . "/Login.php",
        "About_us" => COMPONENTS_PATH . "/About_us.php",
        "Footer" => COMPONENTS_PATH . "/Footer.php",
        "Template_loader" => COMPONENTS_PATH . "/Template_loader.php",
        "Clear_database" => COMPONENTS_PATH . "/Clear_database.php",
    ));

#region Debuging
defined("DEBUG")
    or define("DEBUG", true);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    //trigger_error("DEBUG MODE ON");
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

set_time_limit(0);
ini_set('memory_limit', '20000M');
ignore_user_abort(false);

#endregion

session_start();

require_once(CLASSES_PATH . "/Head_header_manager.php");

foreach (COMPONENTS as $_ => $comp_path) {
    require_once($comp_path);
}
