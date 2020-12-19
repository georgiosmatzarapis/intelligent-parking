<?php

namespace project_web\resources\components\on_top;

require_once(realpath(dirname(__FILE__) . "/../../config.php"));
require_once(realpath(dirname(__FILE__) . "/../Component.php"));

use project_web\resources\components\Component;

class Map_popup extends Component
{
    public function get_body()
    {
        ?>
        <form>
            <div class="form-group mb-3">
                <label for="pop_up_available_places">Διαθέσιμες θέσεις</label>
                <div class="input-group" id="pop_up_available_places">
                    <button type="button" class="input-group-prepend btn btn-primary" onclick="subtract_one()">-</button>
                    <input type="text" class="form-control text-center" aria-label="places" id="pop_up_available_places_input" value="0" onchange="keep_number_value_positive('pop_up_available_places_input', 0)">
                    <button type="button" class="input-group-append btn btn-primary" onclick="add_one()">+</button>
                </div>
            </div>
            <div class="form-group">
                <label for="pop_up_demand_curve_selector">Καμπύλη ζήτησης</label>
                <select class="custom-select form-control" id="pop_up_demand_curve_selector">
                </select>
            </div>
            <div class="form-inline justify-content-end w-100">
                <button id="pop_up_submit" type="button" class="m-1 btn btn-light">
                    <img src="<?= CONFIG["paths"]["img"]["layout"] . "/green-tick.png" ?>" width="25" />
                </button>
            </div>
        </form>
    <?php
    }
}
