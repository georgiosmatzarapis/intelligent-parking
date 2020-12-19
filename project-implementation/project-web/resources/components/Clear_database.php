<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");
require_once(CLASSES_PATH . '/Database_manager.php');

use project_web\resources\library\classes\Database_manager;

class Clear_database extends Component
{
    public function __construct()
    {
        parent::__construct();

        $this->modal_id = "clean_db_modal" . $this->uid;
        $this->submit_uid = "submit" . $this->uid;
        $this->submit_delete_name = $this->submit_uid . "delete";

        $this->database_cleared = false;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST[$this->submit_uid])) {
                return;
            }

            if (isset($_POST[$this->submit_delete_name])) {
                Database_manager::clean_db();
                $this->database_cleared = true;
            }
        }
    }

    public function get_body()
    {
        ?>
        <div class="container-fluid mt-5 mb-5">
            <h2 class="text-center text-primary">Καθαρισμός βάσης</h2>
            <div class="d-flex justify-content-center">
                <p class="col-lg-8 col-md-12 text-center text-light mt-2 mb-3">Με την χρήση του παρακάτω κουμπιού δίνεται η δυνατότητα για καθαρισμό της βάσης</p>
            </div>
            <div class="d-flex justify-content-center">
                <?php
                if ($this->database_cleared == false) :
                    ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?= $this->modal_id ?>">Καθαρισμός βάσης</button>
                <?php
                else :
                    ?>
                    <button type="button" class="btn btn-disable" data-target="#<?= $this->modal_id ?>">Η βάση έχει καθαριστεί!</button>
                <?php
                endif;
                ?>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="<?= $this->modal_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title text-center" id="exampleModalLabel">ΠΡΟΣΟΧΗ!</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">Τα περιεχόμενα της βάσης θα εξαληθφούν.<br>Είστε σίγουροι ότι θέλετε να συνεχισετε;<br><b class="text-danger">Αυτή η ενέργεια δεν είναι αναστρέψιμη.</b></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                            <input class="hidden" type="hidden" name="<?= $this->submit_delete_name ?>">
                            <button type="submit" class="btn btn-danger" name="<?= $this->submit_uid ?>">Καθαρισμός βάσης</button>
                        </form>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Άκυρο</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}
