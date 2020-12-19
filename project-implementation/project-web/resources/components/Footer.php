<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

class Footer extends Component
{
    public function get_body()
    {
        ?>
        <footer class="page-footer bg-dark fixed-bottom">
            <div class="row my-1 text-secondary full-purple-link">
                <div class="col-md-1 d-none d-md-block"></div>
                <a class="text-md-right col-md-5 full-purple-link d-none d-md-block" style="text-decoration: none;" href="<?= CONFIG["ext_urls"]["kamate_url"] ?>"> ©<?= date("Y"); ?> Copyright: kamate.com</a>
                <div class="d-none d-md-block">|</div>
                <a class="text-md-left col-md-5 full-purple-link text-center" style="text-decoration: none;" href="<?= CONFIG["pages"]["about"] ?>">Σχετικά με εμάς</a>
                <div class="col-md-1 d-none d-md-block"></div>
            </div>
        </footer>
        </body>
    <?php
    }
}
