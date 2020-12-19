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

require_once(LIBRARY_PATH . "/third_party/phayes-geoPHP/geoPHP.inc");

//TODO
geoPHP::version();
$db_selection = 'POLYGON((40.642484279427535 22.95226283669054,40.642633124790535 22.952519683733698,40.64258564470404 22.9526335142702,40.64258470552119 22.952653522573225,40.64254530364354 22.952763326345746,40.6425277934071 22.95277271813205,40.64247311727801 22.95290842712425,40.64247223510948 22.95293462912923,40.64240965114211 22.953078686640474,40.64234594051467 22.95317589265445,40.64221535657395 22.953226246733646,40.64220136782371 22.95325065592981,40.64212223786912 22.95320780734829,40.64210141865521 22.953191547454683,40.64208818379471 22.953167547053805,40.6420797484818 22.953123951253687,40.64209476017761 22.952996362074135,40.64211372011004 22.95294506622522,40.642365235846725 22.952526396647855,40.64247275367312 22.952258537108303,40.642484279427535 22.95226283669054))';

$geometry = geoPHP::load($db_selection, 'wkt');

$geojson_writer = new GeoJSON();
$geojson = $geojson_writer->write($geometry);

$geojson_obj = json_decode($geojson, true);
$to_ret = array("1" => $geojson_obj["coordinates"]);
echo json_encode($to_ret);

return;



$obj = (object) array(
    "1" => array(
        array(
            array(40.643012616714856, 22.93400457702626),
            array(40.64362253212651, 22.934564545884665),
            array(40.64362940780963, 22.934701483452614),
            array(40.64273825698915, 22.934523984921857),
        )
    ),
    "2" => array(
        array(
            array(40.6041876647569, 22.954707888974728),
            array(40.60424063324161, 22.955564468266548),
            array(40.60366407325679, 22.95578343654876),
            array(40.60365277429903, 22.955757583533945),
        )
    ),
    "3" => array(
        array(
            array(40.61298719056447, 22.982470873793908),
            array(40.61308162777391, 22.982189365970473),
            array(40.613199365135294, 22.981841865404004),
            array(40.61344259348713, 22.98198543844587),
        )
    ),
);
// print_r($obj);

echo json_encode($obj);

// {"id": [ [ [ ] ] ] }

// {"id":[[[37,-109.05]]]}

// echo '[{"x":"40.643012616714856", "y":"22.93400457702626"}, {"x":"40.64", "y":"22.93"}]';

#endregion
