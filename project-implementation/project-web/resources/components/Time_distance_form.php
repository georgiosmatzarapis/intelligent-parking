<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

require_once(COMPONENTS_PATH . "/includes/Timepicker.php");

use project_web\resources\components\includes\Timepicker;

class Time_distance_form extends Component
{
    public function __construct()
    {
        parent::__construct();

        $this->timepicker = new Timepicker();
    }

    public function get_body()
    {
        ?>
        <div class="container-fluid  bg-secondary">
            <div class="row justify-content-around">
                <div class="my-1 col-12 col-sm-5 col-lg-3">
                    <?php $this->timepicker->get_body(); ?>
                </div>

                <div class="my-1 col-12 col-sm-5 col-lg-3">
                    <div class="m-1 input-group">
                        <input type="text" class="form-control" id="max_distance" aria-label="meters_to_dest" placeholder="Μέγιστη Απόσταση" value="100" onchange="keep_number_value_positive('max_distance', 100)">
                        <button class="input-group-append btn btn-primary"  id="find_parking_spot_submit">Πάμε</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    public function get_js_info()
    {
        return array(
            "timepicker_id" => $this->timepicker->get_timpicker_id(),
            "time_is_set_event_name" => $this->timepicker->get_time_is_set_event_name(),
            "max_distance_field_id" => "max_distance",
            "submit_button_id" => "find_parking_spot_submit",
        );
    }
}
