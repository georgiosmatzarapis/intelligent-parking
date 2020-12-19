<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

require_once(COMPONENTS_PATH . "/includes/Timepicker.php");

use project_web\resources\components\includes\Timepicker;

class Timepick_timesteps_form extends Component
{
    public function __construct($map, $is_admin = false)
    {
        parent::__construct();

        $this->map = $map;
        $this->is_admin = $is_admin;

        $this->timepicker = new Timepicker();
    }

    public function get_body()
    {
        ?>
        <div class="container-fluid bg-secondary">
            <div class="row justify-content-around">
                <?php if ($this->is_admin) : ?>
                    <div class="my-1 col-12 col-sm-5 col-lg-3">
                        <div class="m-1 input-group">
                            <button type="button" class="input-group-prepend btn btn-primary" onclick="subtract_minutes()">&lt;</button>
                            <input type="text" class="form-control text-center" id="minute_input_field" aria-label="time" value="15" onchange="keep_number_value_positive('minute_input_field', 15)">
                            <button type="button" class="input-group-append btn btn-primary" onclick="add_minutes()">&gt;</button>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="my-1 col-12 col-sm-5 col-lg-3">
                    <?php $this->timepicker->get_body(); ?>
                </div>
            </div>
        </div>

        <script defer>
            function subtract_minutes() {
                let raw_minutes_value = $("#minute_input_field").val();
                if (Number.isInteger(parseInt(raw_minutes_value))) {
                    change_timepicker_time_by(-raw_minutes_value);
                }
            }

            function add_minutes() {
                let raw_minutes_value = $("#minute_input_field").val();
                if (Number.isInteger(parseInt(raw_minutes_value))) {
                    change_timepicker_time_by(raw_minutes_value);
                }
            }

            function change_timepicker_time_by(minutes) {
                let timepicker_id = "<?= $this->timepicker->get_timpicker_id() ?>";
                let picker_string_time = $(`#${timepicker_id}`).datetimepicker('viewDate');
                let unix_ms_time = new Date(picker_string_time._d).getTime();
                let final_unix_ms_time = unix_ms_time + minutes * 60 * 1000;
                let final_date = new Date(final_unix_ms_time);
                let final_date_string = `${final_date.getHours()}:${final_date.getMinutes()}`;
                $(`#${timepicker_id}`).datetimepicker('date', moment(final_date_string, "hh:mm A"));

                dispatch_recolor_event(final_date.getHours(), final_date.getMinutes());
            }

            document.addEventListener("<?= $this->timepicker->get_time_is_set_event_name(); ?>", ev => {
                let hours = ev.detail.time.hours;
                let minutes = ev.detail.time.minutes;
                <?php if (DEBUG) : ?>
                    console.log(`[INFO] Creating and dispatching a redraw map event for time: ${hours}h ${minutes}m`);
                <?php endif; ?>
                dispatch_recolor_event(hours, minutes);
            });

            function dispatch_recolor_event(hours, minutes) {
                let event_name = "<?= $this->map->get_settings()['recolor_polygons_event_name'] ?>";
                let event = new CustomEvent(event_name, {
                    detail: {
                        time: {
                            hours: hours,
                            minutes: minutes,
                        },
                    }
                });
                document.dispatchEvent(event);
            }
        </script>
    <?php
    }
}
