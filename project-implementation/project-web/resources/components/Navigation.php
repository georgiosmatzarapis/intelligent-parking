<?php

namespace project_web\resources\components\Navigation {
    class MenuItem
    {
        public $label;
        public $page_path;
        public $extra_classes;

        function __construct($lbl, $pg_pt, $ex_cl)
        {
            $this->label = $lbl;
            $this->page_path = $pg_pt;
            $this->extra_classes = $ex_cl;
        }

        public function echo()
        { ?>
            <li class="nav-item"> <a class="nav-link <?= $this->extra_classes ?> <?= $_SERVER['SCRIPT_NAME'] == $this->page_path ? 'active' : '' ?>" href="<?= $this->page_path ?>"><?= $this->label ?></a></li>
        <?php
        }
    }
}

namespace project_web\resources\components {

    require_once(realpath(dirname(__FILE__) . "/../config.php"));
    require_once("Component.php");

    class Navigation extends Component
    {
        private $userMenuItems;
        private $adminMenuItems;

        private function echo_menu_items($menu_items_array)
        {
            if (is_array($menu_items_array) || is_object($menu_items_array)) {
                foreach ($menu_items_array as $mi) {
                    $mi->echo();
                }
            }
        }

        public function __construct()
        {
            parent::__construct();

            $mi_parking_spot_availability = new Navigation\MenuItem("Διαθεσιμότητα&nbspΘέσεων", CONFIG["navigation_pages_paths"]["parking_spot_availability"], "");
            $mi_find_parking_spot = new Navigation\MenuItem("Εύρεση&nbspΘέσης", CONFIG["navigation_pages_paths"]["find_parking_spot"], "");
            
            $mi_admin_simulation = new Navigation\MenuItem("Εξομοίωση", CONFIG["navigation_pages_paths"]["admin_simulation"], "");
            $mi_admin_administrate_map = new Navigation\MenuItem("Διαχείριση&nbspΧάρτη", CONFIG["navigation_pages_paths"]["admin_administrate_map"], "");
            $mi_admin_administrate_db = new Navigation\MenuItem("Διαχείριση&nbspΒάσης", CONFIG["navigation_pages_paths"]["admin_administrate_db"], "");
            $mi_admin_logout = new Navigation\MenuItem('<i  class="fa fa-sign-out" style="font-weight: 100px !important" ></i>', CONFIG["navigation_pages_paths"]["admin_logout"], "");
            
            $this->userMenuItems = array($mi_parking_spot_availability, $mi_find_parking_spot);
            $this->adminMenuItems = array($mi_admin_simulation, $mi_find_parking_spot, $mi_admin_administrate_map, $mi_admin_administrate_db, $mi_admin_logout);
        }
        
        public function get_body()
        {
            ?>
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark top-bar" data-options="scrolltop: false">
                <div class="d-flex flex-grow-1">
                    <span class="w-100 d-lg-none d-block">
                        <!-- hidden spacer to center brand on mobile --></span>
                    <a class="navbar-brand" href="<?= CONFIG["pages"]["index"] ?>">
                        <span class="d-none d-lg-block"><?= CONFIG["site_title"] ?></span>
                        <b class="d-lg-none"><?= CONFIG["short_site_title"] ?></b>
                    </a>
                    <div class="w-100 text-right">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
                <div class="collapse navbar-collapse flex-grow-1 text-right" id="navbarToggler">
                    <ul class="navbar-nav ml-auto flex-nowrap">
                        <?php
                            if(isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == true)
                            {
                                $this->echo_menu_items($this->adminMenuItems);
                            }
                            else 
                            {
                                $this->echo_menu_items($this->userMenuItems);
                            }
                        ?>
                    </ul>
                </div>
            </nav>
        <?php
        }
    }
}
