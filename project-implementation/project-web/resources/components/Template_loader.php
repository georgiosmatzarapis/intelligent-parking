<?php

namespace project_web\resources\components;

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once("Component.php");

class Template_loader extends Component
{
    public function __construct($body_path, $variables)
    {
        parent::__construct();

        $this->body_path = $body_path;
        $this->variables = $variables;
    }

    public function get_body()
    {
        // making sure passed in variables are in scope of the template
        // each key in the $variables array will become a variable
        if (count($this->variables) > 0) {
            foreach ($this->variables as $key => $value) {
                if (strlen($key) > 0) {
                    ${$key} = $value;
                }
            }
        }
        ?>
        <div class="mt-3 mb-4 text-light">
            <?php require_once($this->body_path) ?>
        </div>
    <?php
    }
}
