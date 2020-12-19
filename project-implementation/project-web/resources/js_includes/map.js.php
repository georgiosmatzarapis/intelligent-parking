<?php require_once(realpath(dirname(__FILE__) . "/../config.php")); ?>
<script defer>
    //Initialise variables from php 
    let top_spaces = JSON.parse('<?= json_encode($top_space) ?>'); //convert php obj. 'top_space' -> json string, 2)convert this json string -> JavaScript obj.(json)
    let bottom_spaces = JSON.parse('<?= json_encode($bottom_space) ?>'); 
    let config = JSON.parse('<?= json_encode($config) ?>'); 

    let local_config = {
        marker_icon_url: "https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png",
        marker_shadow_url: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
    }

    <?php if (DEBUG) : ?>
    console.log(config); 
    <?php endif; ?>

    //Configure map size
    $(window).on("resize", resize); //"resize" => user's action
    resize();

    //Create map instance and add tiles
    let my_map = L.map("<?= $map_id ?>"); //gets the id from the combination of Map & Component classes
    let osm = new L.TileLayer("<?= $tiles_url ?>");

    //Set map view from config TODO
    my_map.setView([40.643012616714856, 22.93400457702626], 16);

    //Add map to page
    my_map.addLayer(osm);

    //Add, color and add popups to polygons
    if (config["show_polygons"] == "true") { //for each page which needs gray or colored polygons
        <?php if (DEBUG) : ?>
        console.log("[INFO] Adding polygons to map");
        <?php endif; ?>
        /*
        1then) exedcute by calling -> parking_spot_availanility.php
        2then) exedcute by calling -> administrate_map.php
        3then) add polygons with its specs(ex. pop ups or not) to map

        Each promise will be executed accoording the map setting of each page
        */
        fetch_polygons_dict(config["polygons_url"])// "/api/polygons_coordinates.php"
            .then(polygons_dict => color_polygons_dict(polygons_dict, (config["color_polygons"] == "true"), config["polygons_availabilities_url"], new Date().getHours(), new Date().getMinutes(), config["recolor_polygons_event_name"]))
            .then(polygons_dict => add_popups_to_polygons_dict(polygons_dict, (config["show_polygons_popup"] == "true"), config["pop_up_url"], config["pop_up_info"], config["polygon_parking_info_url"]))
            .then(polygons_dict => add_polygon_dict_to_map(polygons_dict, my_map))
            .then(polygons_dict => polygons_dictionary = polygons_dict)
            .catch(e => console.log("Add, color and add popups to polygons failed", e));
    }

    var polygons_dictionary; 

    function fill_spots_with_parking_info() { //administrate_map.php [DEBUG] Button √
        for (let id in polygons_dictionary) { 
            let to_post = new FormData(); 
            to_post.append('id', id);
            to_post.append('available_spots', Math.floor(Math.random() * 100) + 10); //random fill of available spots
            to_post.append('curve', Math.ceil(Math.random() * 3)); //random choose 

            polygons_dictionary[id].setStyle({
                color: "orange",
                fillColor: "orange"
            });
            fetch_post_and_parse(config["polygon_parking_info_url"], to_post) //send to API "../admin/api/polygon_parking_info.php" -> Polygon.php for processing
                .then(() => {
                    polygons_dictionary[id].setStyle({
                        color: "gray",
                        fillColor: "gray"
                    });
                });
        }
    }

    //Enable find parking spot abilities
    if (config["enable_find_parking_spot"] == "true") { //find_parking_spot.php
        //Add default marker
        let timepicker_unixtime;
        let default_marker;
        let user_choosen_x;
        let user_choosen_y;

        let form_locked = false;

        let max_distance_input_css_selector = `#${config["find_parking_spot_info"]["max_distance_field_id"]}`; //[$form->get_js_info()]["max_distance"] -> id of the meters input

        fetch_and_parse(config["default_marker_location"]) //"/api/default_pin_location.php"
            .then(marker_array => add_marker_array(my_map, marker_array, true)) //this promise returns an array with many info about pin
            .then(marker => {
                marker.on('mouseup', function(e) { //event when the left mouse button is released
                    let coord = e.latlng;
                    users_choosen_x = coord.lat; //get x
                    users_choosen_y = coord.lng; //get y
                });
                default_marker = marker;
                users_choosen_x = marker.getLatLng().lat;
                users_choosen_y = marker.getLatLng().lng;

                marker.on('drag', function(e) {
                    if (new Date().getTime() % 2 == 0) {
                        return;
                    }

                    //IF TIME AND DISTANCE BUTTONS ARE NOT LOCKED
                    if (!form_locked) { //if meters and hour select are not locked, this means that user didnt find spot and will still looking for available spots, so the next part of code is useless
                        return; //continue to next promise, until user find spot and the buttons will be locked
                    }
                    let coord = e.latlng;
                    users_choosen_x = coord.lat;
                    users_choosen_y = coord.lng;

                    let unixtime = timepicker_unixtime;
                    let max_distance = $(max_distance_input_css_selector).val(); //returns user's input(jquery)
                    let x = users_choosen_x;
                    let y = users_choosen_y;

                    //ex. url = /api/parking_spot.php?unixtime=1568671179&max_distance=90&x=40.64251687671312&y=22.936390659645056, call with time, distance and marker(x,y) as parameteres
                    let url = `${config["parking_spot_url"]}?unixtime=${unixtime}&max_distance=${max_distance}&x=${x}&y=${y}`;

                    let polylines = [];
                    fetch_and_parse(url) //Object { x: 40.643212545583594, y: 22.93345278254921, distance: 0.0005941558327261262, success string }
                        .then(response => {
                            if (response["success"] == "true") { //when we find a spot(centroid of cluster), remove any layer from the map (yellow polyline) and draw the new one
                                function clearMap(m) {
                                    for (i in m._layers) {
                                        if (m._layers[i]._path != undefined) {
                                            try {
                                                m.removeLayer(m._layers[i]);
                                            } catch (e) {
                                                console.log("problem with " + e + m._layers[i]);
                                            }
                                        }
                                    }
                                }

                                clearMap(my_map);

                                response["spots"].forEach(spot => {
                                    let latlngs = [
                                        [x, y],
                                        [spot["x"], spot["y"]],
                                    ];

                                    let polyline = L.polyline(latlngs, {
                                        color: 'yellow'
                                    }).addTo(my_map);

                                });
                            }
                        });
                });
            })
            .then(() => {
                //Add event listener for timepicker - event name: 'time_is_set', created: find_parking_spot.php -> Time_distance_form.php -> Timepicker.php
                document.addEventListener(config["find_parking_spot_info"]["time_is_set_event_name"], ev => {
                    timepicker_unixtime = ev.detail.time.unixtime;
                }); 
                //otherwise take the default time for proccess
                timepicker_unixtime = parseInt((new Date().getTime() / 1000).toFixed(0));
            })
            .then(() => {
                let submit_button_css_selector = `#${config["find_parking_spot_info"]["submit_button_id"]}`; //id for submit button of meters
                let timepicker_css_selector = `#${config["find_parking_spot_info"]["timepicker_id"]}`; //id for submit button of hours,minutes

                //Add onclick function for find parking submit button
                $(submit_button_css_selector).click(() => {
                    <?php if (DEBUG) : ?>
                    console.log(`[INFO] Find parking spot submit pressed!`);
                    <?php endif; ?>

                    let unixtime = timepicker_unixtime;
                    let max_distance = $(max_distance_input_css_selector).val(); //returns user's input(jquery)
                    let x = users_choosen_x;
                    let y = users_choosen_y;

                    //ex. /api/parking_spot.php?unixtime=1568671179&max_distance=90&x=40.64251687671312&y=22.936390659645056
                    let url = `${config["parking_spot_url"]}?unixtime=${unixtime}&max_distance=${max_distance}&x=${x}&y=${y}`;

                    fetch_and_parse(url) //Object { x: 40.643212545583594, y: 22.93345278254921, distance: 0.0005941558327261262, success sting }
                        .then(response => {
                            <?php if (DEBUG) : ?>
                            console.log(`[INFO] parking_spot response`, response);
                            <?php endif; ?>

                            if (response["success"] == "true") { //when a parking spot is found
                                //Disable max_distance and button
                                $(submit_button_css_selector).prop("disabled", true); 
                                $(submit_button_css_selector).addClass("btn-disable"); //css part
                                $(max_distance_input_css_selector).prop("disabled", true);

                                //Disable timepicker
                                $(`${timepicker_css_selector} > input`).prop("disabled", true);
                                $(`${timepicker_css_selector} > button`).prop("disabled", true);
                                $(`${timepicker_css_selector} > button`).addClass("btn-disable");

                                //Lock form
                                form_locked = true;

                                let max_dist = -1;
                                let polyline_to_zoom_to;
                                response["spots"].forEach(spot => {
                                    let latlngs = [
                                        [x, y],
                                        [spot["x"], spot["y"]],
                                    ];

                                    let polyline = L.polyline(latlngs, {
                                        color: 'yellow'
                                    }).addTo(my_map);

                                    if (max_dist < spot["distance"]) {
                                        max_dist = spot["distance"];
                                        polyline_to_zoom_to = polyline;
                                    }
                                });

                                //Zoom the map to the polyline
                                my_map.fitBounds(polyline_to_zoom_to.getBounds());
                            }
                        });

                });
            })
            .catch(e => console.log("Add pins failed", e));

    }
    //==========================================================================================

    //-------------------------------------------------------------------------------------------
    function resize() { // √  
        window_width = window.innerWidth; //get width
        for (const [key, value] of Object.entries(top_spaces)) { //returns an array consisting of enumerable property [key, value] pairs of the object(top_spaces) 
            if (window_width > key)
                var top_space = value;
        }
        for (const [key, value] of Object.entries(bottom_spaces)) {
            if (window_width > key)
                var bottom_space = value;
        }
        let map_top = parseInt(top_space);
        let map_height = window.innerHeight - map_top - parseInt(bottom_space); //final height, without footer,navigation and any bar
        $('#<?= $map_id ?>').css("height", map_height); //for the specific map_id(according to the id that is given from the 'Component' class for each page)
        $('#<?= $map_container_id ?>').css("top", map_top); //for the specific map_container_id
    }
    //-------------------------------------------------------------------------------------------
    function fetch_and_parse(url) { //("/api/polygons_coordinates.php"), get coordinates (1st call), [DEBUG] For each promise .then(_ => {console.log(_)}),    √  
        return fetch(url, {
                credentials: 'include', // send user credentials (cookies, basic http auth, etc..)
            })
            .then(_ => _.text()) //returns a string encoded json with all coordinates   
            .then(_ => JSON.parse(_)) //convert to json format
            .catch(e => console.log("fetch_and_parse failed", e));
    }
    //-------------------------------------------------------------------------------------------
    function fetch_post_and_parse(url, data) { //  √
        return fetch(url, {
                credentials: 'include',
                method: "POST",
                body: data
            })
            .then(_ => _.text())
            .then(_ => JSON.parse(_))
            .catch(e => console.log("fetch_and_parse failed", e));
    }
    //-------------------------------------------------------------------------------------------
    function fetch_polygons_dict(polygons_url) { //Gray colored polygons  √
        return fetch_and_parse(polygons_url) //fetch_and_parse() returns coordinates in json format
            .then(_ => { //with this json, do
                var polygons_dict = {};
                for (let key in _) { 
                    let points = _[key]; //each var. points has the coordinates of each polygon
                    let polygon = L.polygon(points, {
                        color: "gray",
                        fillColor: "gray"
                    });
                    polygons_dict[key] = polygon;
                } 
                return polygons_dict; //contains id for each polygon, etc ...
            })
            .catch(e => console.log("fetch_polygons_dict failed", e));
    }
    //-------------------------------------------------------------------------------------------
    function add_marker_array(map, markers_array, draggable) { //marker_array has the default pin location  √
        var icon = new L.Icon({
            iconUrl: local_config.marker_icon_url,
            shadowUrl: local_config.marker_shadow_url,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        let marker; //To return the latest added marker
        markers_array.forEach(marker_location => {
            marker = L.marker([marker_location["x"], marker_location["y"]], {
                draggable: draggable,
                mobile: true,
                webkit: true,
                icon: icon
            }).addTo(map);
        });

        return marker;
    }
    //-------------------------------------------------------------------------------------------
    //                          (gray polygons, color_polyg = true, /api/polygons_availabilities.php, hour, minutes, event:recolor_polygons )
    function color_polygons_dict(polygons_dict, should_color, availabilities_url, hours, minutes, recolor_event_name) { //FUNCTION FOR COLORING FOR THE CURRENT SYSTEM HOUR || USER'S INPUT HOUR    √

        function do_color_polygon_dict(polygons_dict, availabilities_url, hours, minutes) { //1st EXEC OF FUNCTION FOR THE CURRENT SYSTEM HOUR
            let request_url = `${availabilities_url}?hours=${hours}&minutes=${minutes}`; // /api/polygons_availabilities.php?hours=13&minutes=15, request with this parameters

            return fetch_and_parse(request_url) //get json with centroid and percentage of taken places for each polygon
            <?php if (DEBUG) : ?>
                .then(rsp => {
                    console.log(`[INFO][do_color_polygon_dict] availabilities api fetch: `, rsp);
                    return rsp;
                })
            <?php endif; ?>
                .then(rsp => { //rsp has the json with centro, perc
                    let polygons_availabilities_dict = {};
                    for (let key in rsp) { 
                        let percentage = rsp[key]["perc"]; 
                        polygons_availabilities_dict[key] = percentage;
                    } 
                    return polygons_availabilities_dict; //return percentage 
                })
                .then(polygons_availabilities_dict => {
                    for (let id in polygons_dict) { //for each gray polygon, match its id and percentage 
                        let percentage = parseFloat(polygons_availabilities_dict[id]); //percentage for each polygon
                        let color, fill_color;

                        if (percentage < 0.60) {
                            color = fill_color = "green";
                        } else if (percentage > 0.84) {
                            color = fill_color = "red";
                        } else {
                            color = "yellow";
                        }

                        polygons_dict[id].setStyle({ //add to polygon array attributes
                            color: color,
                            fillColor: fill_color
                        });
                    }
                })
                .then(() => polygons_dict)
                .catch(e => console.log("do_color_polygon_dict failed", e));
        }


        if (!should_color) //"color_polygons" => false
            return polygons_dict; //return gray polygons, useful for file ./public_html/admin/administrate_map.php

        //return IN CASE that user or admin change the time they want to see availability
        return do_color_polygon_dict(polygons_dict, availabilities_url, hours, minutes)
            .then(polygons_dict => {
                //RETRIEVE HOUR,MINUTES OF USER OR ADMIN INPUT
                document.addEventListener(recolor_event_name, ev => { //event created in file ./project-web/resources/components/Timepick_timesteps_form.php
                    let hours = ev.detail.time.hours;
                    let minutes = ev.detail.time.minutes;

                    do_color_polygon_dict(polygons_dict, availabilities_url, hours, minutes)
                    <?php if (DEBUG) : ?>
                        .then(console.log(`[INFO] Polygons recolored for time: ${hours}h ${minutes}m`));
                    <?php endif; ?>

                })
            })
            .then(_ => polygons_dict) //update polygons_dict with color
            .catch(e => console.log("color_polygons_dict failed", e));
    }
    //-------------------------------------------------------------------------------------------
    //                                  (gray polygons, "show_polygons_popup" == "true", "/admin/pop_up.php", config["pop_up_info"], "/admin/api/polygon_parking_info.php")
    function add_popups_to_polygons_dict(polygons_dict, should_add, pop_up_url, pop_up_info, polygon_parking_info_url) {//   √
        if (!should_add)
            return polygons_dict;

        //JQuery -> add DEBUG button at .../admin/administrate_map.php, onclick = "fill_spots_with_parking_info()"
        $(document.body).append('<button class="btn btn-warning" style="z-index: -1; position: fixed; top: 67px; left:53px"onclick="fill_spots_with_parking_info()">[DEBUG] Γέμισμα με τυχαία parking info <span hidden id="debug_spinner" class="spinner-grow spinner-grow-sm" role="status"></span></button>');

        var popupOptions = {
            'minWidth': '200',
            'maxWidth': '200',
            'closeButton': false,
            'className': 'another-popup' // classname for another popup
        }

        return fetch(pop_up_url) //retun all text(Map_popup.php)
            .then(_ => _.text())
            .then(pop_up_page => {
                for (let id in polygons_dict) { //for each gray polygon
                    let polygon = polygons_dict[id];
                    let pop_up_id = `pop_up_${id}`; 

                    // From the retrieved .text() set variables which include polygon id(pop_up_id) and the proper html id
                    let available_spots_input_css_selector = `#${pop_up_id} #${pop_up_info["pop_up_available_places_input_id"]}`; 
                    let demand_curve_select_css_selector = `#${pop_up_id} #${pop_up_info["pop_up_demand_curve_selector_id"]}`;
                    let submit_css_selector = `#${pop_up_id} #${pop_up_info["pop_up_submit_button_id"]}`;

                    //Wrap the pop_up.php with the current id of the gray poylygon
                    final_pop_up_page = $('<div/>', {
                        id: pop_up_id,
                    }).append(pop_up_page).prop('outerHTML'); //set the append part to the selected element, '<div/>'
                    
                    //Bind popup
                    polygon.bindPopup(final_pop_up_page, popupOptions)

                    //Add event handlers
                    polygon.on('popupopen', popup => {
                        <?php if (DEBUG) : ?>
                        console.log(`[INFO] Popup on polygon with id:${id} opened!`);
                        <?php endif; ?>

                        //Fetch polygon id pop up info
                        let request_url = `${polygon_parking_info_url}?id=${id}`;  //"/admin/api/polygon_parking_info.php&id" with current id as paremeter
                        fetch_and_parse(request_url) //get in json format success -> true, polygon id, polygon parking_spots(if exist), polygon demand_curve_id, polygon label
                            .then(response => update_pop_up_values(response));

                        //when click on pop-up's submit, post the data (curve, parking spots) to the api "../admin/api/polygon_parking_info.php" -> Polygon.php for processing -> return data back to map.js.php to update the fields
                        document.querySelector(submit_css_selector) //for the specific id
                            .onclick = _ => {
                                <?php if (DEBUG) : ?>
                                console.log(`[INFO] Submit pressed on polygon with id:${id}!`);
                                <?php endif; ?>

                                let to_post = new FormData(); //construct a set of key/value pairs
                                to_post.append('id', id);
                                to_post.append('available_spots', $(available_spots_input_css_selector).val()); //the update value to 'available_spots'
                                to_post.append('curve', $(`${demand_curve_select_css_selector} :selected`).val()); //the selected value(jquery line 457) to 'curve'

                                fetch_post_and_parse(polygon_parking_info_url, to_post) //send to api the below info
                                    .then(response => update_pop_up_values(response)); //the api sends back the new info
                            };
                    });

                    function update_pop_up_values(response) {
                        <?php if (DEBUG) : ?>
                        console.log(`[INFO] Fetched response from polygon_parking_info`, response);
                        <?php endif; ?>
                        if (!(response["success"] == "true")) { //for the case that there are no given parking_spots
                            return;
                        }
                        available_spots = response["available_spots"];
                        curve = response["curve"]; //demand_curve_id
                        curves = response["curves"]; //label

                        //Update input field 
                        $(available_spots_input_css_selector).val(available_spots); //update this field with available spots taken from the api "/admin/api/polygon_parking_info.php"

                        //Update select field...
                        //...remove existing options...
                        $(demand_curve_select_css_selector).empty();
                        //...add to the field with the specific if all new options...(Κέντρο Πόλης,Περιοχή Κατοικίας, Περιοχή Σταθερής Ζήτησης)
                        for (let id in curves) {
                            $(demand_curve_select_css_selector).append($('<option>', {
                                value: id,
                                text: curves[id]
                            })); 
                        }
                        //...select the fetched option...
                        let to_select = `${demand_curve_select_css_selector} option:contains('${curves[curve]}')`; //ex. #pop_up_23 #pop_up_demand_curve_selector option:contains('Κέντρο Πόλης') 
                        $(to_select).prop({
                            selected: true
                        });
                    }
                }
                return polygons_dict;
            })
            .then(_ => polygons_dict) //polygon_dict with pop ups
            .catch(e => console.log(e));
    }
    //-------------------------------------------------------------------------------------------
    function add_polygon_dict_to_map(polygons_dict, map) { //  √
        for (let id in polygons_dict)
            polygons_dict[id].addTo(map); 
        return polygons_dict;
    }
    //-------------------------------------------------------------------------------------------
    //-------------------------------------------------------------------------------------------
</script>