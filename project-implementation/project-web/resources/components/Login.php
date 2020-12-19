<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

class Login extends Component
{
    public function __construct()
    {
        parent::__construct();

        $this->failed_login_message = "";
        $this->username_field_name = "username";
        $this->password_field_name = "password";

        $this->post_username = "";

        $this->run_logout = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_SESSION['username'])) {
                session_destroy();
            }
            if (count($_POST) > 0) {
                if (empty($_POST[$this->username_field_name])) {
                    $this->failed_login_message = "Το όνομα χρήστη είναι υποχρεωτικό!";
                    return;
                } else {
                    $this->post_username = htmlspecialchars($_POST[$this->username_field_name]);
                }

                if (empty($_POST[$this->password_field_name])) {
                    $this->failed_login_message = "Πρέπει να δώσετε ένα κωδικό!";
                    return;
                } else {
                    $password = htmlspecialchars($_POST[$this->password_field_name]);
                }

                if ($this->post_username == "admin" && $password == "admin") {
                    $_SESSION['username'] = $this->post_username;
                    $_SESSION['isadmin'] = true;
                    header("location: " . CONFIG["pages"]["index"]);
                } else {
                    $this->failed_login_message = "Τα στοιχεία που δώσατε είναι λανθασμένα!";
                }
            }
        } else if (isset($_SESSION['username'])) {
            $this->run_logout = true;
            session_destroy();
        }
    }
    public function get_body()
    {
        if ($this->run_logout == true) {
            ?>
            <div class="container-fluid mt-3 mb-4 text-light text-center">
                <h3>Αντίο <span class="text-primary"><?= $_SESSION['username'] ?></span></h3>
                <h4>Αποσυνδεθήκατε με επιτυχία!</h4>
                <p><i>Κλείστε το παραθυρο του περιηγητή σας για να ολοκληρωθεί η αποσύνδεση.</i></p>
            </div>
        <?php
        } else {
            ?>
            <form class="container-fluid mt-3 mb-4 text-light" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <div class="d-flex justify-content-center">
                    <div class="form-group col-12 col-md-12 col-lg-6">
                        <label for="username_input">Όνομα διαχειριστή</label>
                        <input id="username_input" name="<?= $this->username_field_name ?>" type="text" class="form-control" placeholder="Όνομα" value="<?= $this->post_username ?>">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="form-group col-12 col-md-12 col-lg-6">
                        <label for="password_input">Κωδικός</label>
                        <input id="password_input" name="<?= $this->password_field_name ?>" type="password" class="form-control" placeholder="Κωδικός">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <div class="form-group col-12 col-md-12 col-lg-6 ">
                        <button id="submit" type="submit" class="p-2 w-100 btn btn-primary">Σύνδεση</button>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="form-group col-12 col-md-12 col-lg-6 ">
                        <label><?= $this->failed_login_message ?></label>
                    </div>
                </div>
            </form>
        <?php
        }
    }
}
