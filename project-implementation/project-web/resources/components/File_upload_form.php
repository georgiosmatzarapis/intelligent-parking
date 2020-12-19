<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

require_once(CLASSES_PATH . "/Head_header_manager.php");
require_once(CLASSES_PATH . '/Polygon.php');
require_once(CLASSES_PATH . '/Database_manager.php');

use project_web\resources\library\classes\Head_header_manager;
use project_web\resources\library\classes\Polygon;
use project_web\resources\library\classes\Database_manager;

class File_upload_form extends Component
{
    public function __construct()
    {
        parent::__construct();

        $this->submit_uid = "submit" . $this->uid;
        $this->submit_upload_file = $this->submit_uid . "uploadfile";

        $this->upload_action_happened = false;
        $this->file_uploaded_error = false;
        $this->file_uploaded_message = "Το αρχείο ανέβηκε με επιτυχία!";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST[$this->submit_uid])) {
                return;
            }

            $this->upload_action_happened = true;

            $this->file_uploaded_error = ($_FILES[$this->submit_upload_file]["error"] != UPLOAD_ERR_OK);
            if ($this->file_uploaded_error == true) {
                $error_message = "";
                switch ($_FILES[$this->submit_upload_file]["error"]) {
                    case UPLOAD_ERR_INI_SIZE:
                        $error_message = "UPLOAD_ERR_INI_SIZE";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $error_message = "UPLOAD_ERR_FORM_SIZE";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error_message = "UPLOAD_ERR_PARTIAL";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $error_message = "UPLOAD_ERR_NO_FILE";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error_message = "UPLOAD_ERR_NO_TMP_DIR";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error_message = "UPLOAD_ERR_CANT_WRITE";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $error_message = "UPLOAD_ERR_EXTENSION";
                        break;
                }
                $this->file_uploaded_message = "Υπήρξε πρόβλημα κατά το ανέβασμα του αρχείου (" . $error_message . ")...";
                return;
            }

            if (!is_uploaded_file($_FILES[$this->submit_upload_file]["tmp_name"])) {
                $this->file_uploaded_message = "Το αρχείο δεν ανέβηκε!";
                return;
            }

            //Handle uploaded file
            
            $kml = simplexml_load_file($_FILES[$this->submit_upload_file]["tmp_name"]);

            $id_counter = 0;
            foreach ($kml->Document->Folder->Placemark as $placemark) {
        
                #region Get population
                $population = 0;
                $dom = new \DOMDocument;
                $dom->loadHTML($placemark->description);
                foreach ($dom->getElementsByTagName('li') as $node) {
                    if ($node->getElementsByTagName('span')->item(0)->textContent == 'Population') { //if the first span tag has value equal to population
                        $population = $node->getElementsByTagName('span')->item(1)->textContent; //get the actual population
                        break;
                    }
                }
                #endregion
        
                #region Get coordinates and create and insert polygon
                if (isset($placemark->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates)) {
                    $linear_ring_coordinates = $placemark->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
                    $polygon = Polygon::create_polygon_for_insert($id_counter, $population, $linear_ring_coordinates);
                    Database_manager::add_insert_statement_for_async_exec($polygon->to_sql_insert());
                    $id_counter += 1;
                }
                #endregion
        
            }
        
            Database_manager::clean_db();

            Database_manager::execute_inserts();
        }
    }

    public function get_body()
    {
        ?>
        <div class="container-fluid text-light mt-3 mb-5">
            <h2 class="text-center text-primary ">Ανέβασμα αρχείου KML</h2>
            <div class="d-flex justify-content-center">
                <p class="col-lg-8 col-md-12 text-center mt-2 mb-3"> Επιλέξτε το αρχείο KML που θα χρησιμοποιηθεί στο <?= CONFIG["site_title"] ?></p>
            </div>
            <div class="d-flex justify-content-center">
                <form class="col-lg-8 col-md-12 my-1 " enctype="multipart/form-data" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input <?= ($this->upload_action_happened) ? (($this->file_uploaded_error) ? "is-invalid" : "is-valid") : "" ?>" id="validatedCustomFile" name="<?= $this->submit_upload_file ?>">
                        <label class="custom-file-label" for="validatedCustomFile">Επιλογή Αρχείου...</label>
                        <div class="invalid-feedback"><?= $this->file_uploaded_message ?></div>
                        <div class="valid-feedback"><?= $this->file_uploaded_message ?></div>
                    </div>
                    <div class="w-100 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary" name="<?= $this->submit_uid ?>" onclick="$('#spinner').removeAttr('hidden')">
                            Ανέβασμα αρχείου
                            <span hidden id="spinner" class="spinner-grow spinner-grow-sm" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }
}
