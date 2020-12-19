<?php
namespace project_web\resources\components;

abstract class Component
{
    protected static $increment = 0;
    function __construct()
    {
        Component::$increment += 1;
        $this->uid = Component::$increment;
    }

    // Force Extending class to define this method
    abstract public function get_body();

    // Common method
    // public function echo_body() {
    //     echo $this->get_body();
    // }
}