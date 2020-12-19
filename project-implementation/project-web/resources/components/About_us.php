<?php

namespace project_web\resources\components\About_us {
    class About_us_person
    {
        public $image_path;
        public $name;
        public $email;

        function __construct($image_path, $name, $email)
        {   
            $this->image_path = $image_path;
            $this->name = $name;
            $this->email = $email;
        }

        public function echo()
        { ?>
            <div class="col col-12 col-lg-4 my-3">
                <img class="w-100 true-shadow" src="<?= $this->image_path ?>">
                <div class="text-center mt-1">
                    <p class="mb-0 text-center font-weight-bold about-us-name"><?= $this->name ?></p>
                    <a class="mt-0 text-center" href="mailto:<?= $this->email ?>"><?= $this->email ?></a>
                </div>
            </div>
        <?php
        }
    }
}

namespace project_web\resources\components {

    require_once(realpath(dirname(__FILE__) . "/../config.php"));
    require_once("Component.php");

    class About_us extends Component
    {
        private $about_us_persons;

        public function __construct()
        {
            parent::__construct();

            $thanasis = new About_us\About_us_person(CONFIG["paths"]["img"]["content"] . "/thanasis.png", "ΚΑΡΑΜΗΤΣΟΣ ΑΘΑΝΑΣΙΟΣ", "karamitsos@ceid.upatras.gr");
            $george = new About_us\About_us_person(CONFIG["paths"]["img"]["content"] . "/george.png", "ΜΑΤΖΑΡΑΠΗΣ ΓΕΩΡΓΙΟΣ", "matzarapis@ceid.upatras.gr");
            $michael = new About_us\About_us_person(CONFIG["paths"]["img"]["content"] . "/michael.png", "ΤΕΡΕΖΑΚΗΣ ΜΙΧΑΗΛ", "mterezakis@ceid.upatras.gr");

            $this->about_us_persons = array($thanasis, $george, $michael);
        }

        private function echo_about_people()
        {
            $persons_array = $this->about_us_persons;
            if (is_array($persons_array) || is_object($persons_array)) {
                foreach ($persons_array as $pa) {
                    $pa->echo();
                }
            }
        }

        public function get_body()
        {
            ?>
            <div class="container mt-3 mb-4 text-light">
                <div class="row ">
                    <h2 class="col text-center text-primary">ΣΧΕΤΙΚΑ ΜΕ ΕΜΑΣ</h2>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <p class="text-justify">Τρείς καλοί φίλοι αποφάσισαν ένα απόγευμα να δημιουργήσουν κάτι που θα κάνει ευκολότερη τη καθημερινότητα τους αλλά και αυτή των συμπολιτών τους. Την επόμενη μέρα το αποτέλεσμα βρίσκεται μπροστά στα μάτια σας.</p>
                    </div>
                </div>
                <div class="row">
                    <?= $this->echo_about_people(); ?>
                </div>
                <div class="row mt-4">
                    <h3 class="col text-left">Ενισχύστε μας:</h3>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-justify">
                            Το προτζεκτ υλοποιήθηκε με προσωπικό κόστος των συνεργαζόμενων και προσφέρεται δωρεάν προς χρήση στους ενδιαφερόμενους. Αν επιθυμείτε να στηρίξετε μελλοντικά προτζεκτ μπορείτε να πραγματοποιήσετε δωρεά στο
                            <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">kamate@paypal.com</a>
                        </p>
                    </div>
                </div>

            </div>
        <?php
        }
    }
}
