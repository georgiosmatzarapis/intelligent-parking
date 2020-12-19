<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
//needs: 
//error_message -> The error message as title
//status_code -> The http status code

?>

<div class="container-fluid">
    <div class="row">
        <p class="col text-center" style="font-size:3rem"><?= $error_message ?></p>
    </div>

    <div class="row align-items-center justify-content-center">
        <p class="mr-lg-4 text-primary" style=" font-size:10rem;"><?= $status_code ?></p>
        <div class="col col-12 col-lg-4">
            <img class="mx-lg-4 w-100" src="<?= CONFIG["paths"]["img"]["layout"] . "/null_island.png" ?>">
        </div>
    </div>

    <div class="row">
        <p class="col mt-4 text-center" style="font-size:1.5rem">Δεν υπάρχουν διαθέσιμες θέσεις στάθμευσης στο <a class="full-purple-link" href="<?= CONFIG["ext_urls"]["null_island"] ?>">Null Island</a>!</p>
    </div>
</div>