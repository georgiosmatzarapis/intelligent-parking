<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));

#region Uses
use project_web\resources\library\classes\Head_header_manager;
#endregion

#region Component instantiation
#endregion

#region Print Body
// Head_header_manager::print_html_header_and_head();
Head_header_manager::print_json_header();


// var latlngs = 
// [
//     [ // first polygon
//       [[37, -109.05],[41, -109.03],[41, -102.05],[37, -102.04]], // outer ring
//     ]
// ];

// {
//     "1": {
//         "cent":{"x":"12.34", "y":"43.21"},
//         "perc":"0.123"
//     }
// }
$obj;

if (isset($_GET["hours"]) && isset($_GET["minutes"])) {
    $hours = htmlspecialchars($_GET["hours"]);
    $minutes = htmlspecialchars($_GET["minutes"]);

    if ($hours > 20) {
        $obj = (object) array(
            "1" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.123
            ),
            "2" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.76
            ),
            "3" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.90
            ),
        );
    } else {
        $obj = (object) array(
            "1" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.90
            ),
            "2" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.123
            ),
            "3" => array(
                "cent" => array(12.34, 43.21),
                "perc" => 0.76
            ),
        );
    }
}

echo json_encode($obj);

// {"id": [ [ [ ] ] ] }

// {"id":[[[37,-109.05]]]}

// echo '[{"x":"40.643012616714856", "y":"22.93400457702626"}, {"x":"40.64", "y":"22.93"}]';

#endregion
