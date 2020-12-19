<?php
require_once(realpath(dirname(__FILE__) . "/../../../resources/config.php"));
require_once(REQUIRES_PATH . "/protect_admin_only_page.php");

#region Uses
use project_web\resources\library\classes\Head_header_manager;
#endregion

#region Component instantiation
function handle_get($curves)
{
    if (!isset($_GET["id"])) {
        return array("success" => "false");
    }

    $id = htmlspecialchars($_GET["id"]);

    if ($id == 1) {
        return array(
            "success" => "true",
            "id" => 1,
            "available_spots" => 14,
            "curve" => "2",
            "curves" => $curves,
        );
    }

    if ($id == 2) {
        return array(
            "success" => "true",
            "id" => 2,
            "available_spots" => 7,
            "curve" => "1",
            "curves" => $curves,
        );
    }

    if ($id == 3) {
        return array(
            "success" => "true",
            "id" => 3,
            "available_spots" => 56,
            "curve" => "3",
            "curves" => $curves,
        );
    }

    return array("success" => "false");
}

function handle_post($curves)
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

    return array(
        "success" => "true",
        "id" => $id,
        "available_spots" => $available_spots,
        "curve" => $curve,
        "curves" => $curves,
    );
}


#endregion

#region Print Body
Head_header_manager::print_json_header();

$curves = array(
    "1" => "Κέντρο Πόλης",
    "2" => "Περιοχή Κατοικίας",
    "3" => "Περιοχή σταθερής ζήτησης",
);

$response;


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = handle_get($curves);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = handle_post($curves);
}

echo json_encode($response);



#endregion
