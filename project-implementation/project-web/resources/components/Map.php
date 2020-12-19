<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

require_once(CLASSES_PATH . "/Head_header_manager.php");

use project_web\resources\library\classes\Head_header_manager;

class Map extends Component
{
    private $settings;

    public function __construct($settings, $top_space, $bottom_space)
    {
        parent::__construct();

        $this->settings = $settings;
        $this->top_space = $top_space;
        $this->bottom_space = $bottom_space;

        $this->map_container_id = $this->uid . "map_container";
        $this->map_id = $this->uid . "map";

        Head_header_manager::add_style_to_head("https://unpkg.com/leaflet@1.3.4/dist/leaflet.css");
        Head_header_manager::add_script_to_head("https://unpkg.com/leaflet@1.3.4/dist/leaflet.js");
    }

    public function get_body()
    {

        ?>
        <div id="<?= $this->map_container_id ?>" style="position: fixed; z-index:-1;">
            <div id="<?= $this->map_id ?>" class="bg-secondary" style="width: 100vw;"></div>
        </div>
        <?php
        $this->get_script();
    }

    public function get_script()
    {
        // // making sure passed in variables are in scope of the template
        // // each key in the $variables array will become a variable
        // if (count($this->settings) > 0) {
        //     foreach ($this->settings as $key => $value) {
        //         if (strlen($key) > 0) {
        //             ${$key} = $value;
        //         }
        //     }
        // }
        $map_container_id = $this->map_container_id;
        $map_id = $this->map_id;
        $top_space = $this->top_space;
        $bottom_space = $this->bottom_space;
        $config = $this->settings;
        // $tiles_url = "http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}";
        $tiles_url = "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png";
        
        require_once(JS_INCLUDES_PATH . "/map.js.php"); 
    }

    public function get_settings()
    {
        return $this->settings;
    }
}
