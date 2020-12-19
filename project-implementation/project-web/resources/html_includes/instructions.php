<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
?>

<div class="container">
    <div class="row">
        <h2 class="col text-center text-primary">Καλώς ήρθατε στο <b><?= CONFIG["site_title"] ?></b></h2>
    </div>
    <div class="row mt-3">
        <h4 class="col text-center">Το πληρέστερο και <b>ΔΩΡΕΑΝ</b> σύστημα εύρεσης θέσης στάθμευσης.</h4>
    </div>
    <div class="row mt-3 align-items-center">
        <div class="col col-12 col-lg-4 d-none d-lg-block">
            <img class="w-100" src="<?= CONFIG["paths"]["img"]["content"] . "/parking-sign.png" ?>">
        </div>
        <div class="col">
            <p class="text-justify further-apart-text">
                <span style="font-size:3rem">1</span> &nbsp Τοποθετήστε την πινέζα στον προορισμό σας.
                <br>
                <span style="font-size:3rem">2</span> &nbsp Επιλέξτε πόσο κοντά θέλετε να ψάξουμε για θέσεις.
                <br>
                <span style="font-size:3rem">3</span> &nbsp Πατήστε &nbsp<button type="button" class="btn btn-primary">Πάμε</button>
                <br><br>
                Το σύστημα θα βρει αμέσως την πιο κοντινή διαθέσιμη θέση στάθμευσης στον προορισμό σας!
                <br><br>
                <i>Για τον έλεγχο της διαθεσιμότητας θέσεων στάθμευσης για μια συγκεκριμένη ώρα, αλλάξτε το πεδίο με την τωρινή ώρα στην ώρα που επιθυμείτε.</i>
            </p>
        </div>
    </div>
</div>