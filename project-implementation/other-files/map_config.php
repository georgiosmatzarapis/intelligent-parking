$map_settings = array(
    "camera_position_url" => "/api/camera_position",

    "enable_find_parking_spot" => "false",
    "default_marker_location" => "/api/pin_location.php",
    "parking_spot_url" => "/api/parking_spot.php",
    "find_parking_spot_info" => array(
        "timepicker_id" => "",
        "time_is_set_event_name" => "",
        "max_distance_field_id" => "",
        "submit_button_id" => "",
    ),
    
    
    "show_polygons" => "false",
    "polygons_url" => "/api/polygons",

    "color_polygons" => "false",
    "polygons_availabilities_url" => "/api/polygons_availabilities.php",
    "recolor_polygons_event_name" => "asdf",

    "show_polygons_popup" => "false",
    "pop_up_url" => "/resources/",
    "pop_up_info" => array(
        "pop_up_available_places_input_id" => "pop_up_available_places",
        "pop_up_demand_curve_selector_id" => "pop_up_demand_curve_selector",
        "pop_up_submit_button_id" => "pop_up_submit",
    )
    "polygon_parking_info_url" => "/admin/api/polygon_parking_info.php",
);

"top" => array(
    "0" => "100",
    "700" => "50",
)
"bottom" => array(
    "0" => "10",
    "700" => "20",
)