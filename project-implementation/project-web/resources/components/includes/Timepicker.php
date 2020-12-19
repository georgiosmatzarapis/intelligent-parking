<?php

namespace project_web\resources\components\includes;

require_once(realpath(dirname(__FILE__) . "/../../config.php"));
require_once(COMPONENTS_PATH . "/Component.php");

require_once(CLASSES_PATH . "/Head_header_manager.php");

use project_web\resources\library\classes\Head_header_manager;

use project_web\resources\components\Component;

class Timepicker extends Component
{
    public function __construct($is_admin = false)
    {
        parent::__construct();

        $this->is_admin = $is_admin;

        Head_header_manager::add_script_to_head("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js");
        Head_header_manager::add_script_to_head("https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/el.js");
        Head_header_manager::add_script_to_head("https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js");

        Head_header_manager::add_style_to_head("https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css");
        Head_header_manager::add_style_to_head("https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css");
    }

    public function get_body()
    {
        ?>
        <div class="m-1 input-group date" id="timepicker" data-target-input="nearest">
            <div class="input-group-prepend" data-target="#timepicker" data-toggle="datetimepicker">
                <div class="input-group-text bg-primary border-0">
                    <i class="fa fa-clock-o text-light"></i>
                </div>
            </div>
            <input type="text" class="form-control text-center datetimepicker-input " data-target="#timepicker" />
            <button type="button" class="input-group-append btn btn-primary" onclick="trigger_time_set_event()">OK</button>
        </div>
        <script>
            $('#timepicker').datetimepicker({
                defaultDate: new Date(),
                format: 'hh:mm A'
            });

            function trigger_time_set_event() {
                let picker_string_time = $('#timepicker').datetimepicker('viewDate');
                let date_object = new Date(picker_string_time._d);

                let time_to_ret = {
                    date_object: date_object,
                    time: date_object.getTime(),
                    hours: date_object.getHours(),
                    utc_hours: date_object.getUTCHours(),
                    minutes: date_object.getMinutes(),
                    utc_minutes: date_object.getUTCMinutes(),
                    unix_time: parseInt((date_object.getTime() / 1000).toFixed(0)),
                }

                let event = new CustomEvent('time_is_set', {
                    detail: {
                        time: time_to_ret,
                    }
                });
                document.dispatchEvent(event);
            }
        </script>
    <?php
    }

    public function get_time_is_set_event_name()
    {
        return "time_is_set";
    }

    public function get_timpicker_id()
    {
        return "timepicker";
    }
}
