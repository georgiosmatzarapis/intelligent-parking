<?php

namespace project_web\resources\library\classes;

require_once(realpath(dirname(__FILE__) . "/../../config.php"));

class Head_header_manager
{
    private static $appended_common_head_contents = array();
    private static $appended_head_contents = array();

    private static function add_script_to_array(& $array, $script_path)
    {
        $array[] = '<script type="text/javascript" src="' . $script_path . '" charset="UTF-8"></script>';
    }

    private static function add_late_exec_script_to_array(& $array, $script_path)
    {
        $array[] = '<script type="text/javascript" src="' . $script_path . '" charset="UTF-8" defer></script>';
    }

    private static function add_style_to_array(& $array, $style_path)
    {
        $array[] = '<link rel="stylesheet" href="' . $style_path . '"/>';
    }

    public static function add_script_to_head($script_path)
    {
        Head_header_manager::add_script_to_array(Head_header_manager::$appended_head_contents, $script_path);
    }

    public static function add_late_exec_script_to_head($script_path)
    {
        Head_header_manager::add_late_exec_script_to_array(Head_header_manager::$appended_head_contents, $script_path);
    }

    public static function add_style_to_head($style_path)
    {
        Head_header_manager::add_style_to_array(Head_header_manager::$appended_head_contents, $style_path);
    }

    private static function add_common_stylesheets_and_scripts()
    {
        foreach (COMMON_STYLESHEETS as $name => $link) {
            Head_header_manager::add_style_to_array(Head_header_manager::$appended_common_head_contents, $link);
        }
        foreach (COMMON_SCRIPTS as $name => $link) {
            Head_header_manager::add_script_to_array(Head_header_manager::$appended_common_head_contents, $link);
        }
        foreach (COMMON_DEFERED_SCRIPTS as $name => $link) {
            Head_header_manager::add_late_exec_script_to_array(Head_header_manager::$appended_common_head_contents, $link);
        }
    }

    public static function print_html_header_and_head()
    {
        header('Content-Type: text/html; charset=utf-8');
        Head_header_manager::add_common_stylesheets_and_scripts();
        ?>
        <!DOCTYPE html>

        <head>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
            <meta http-equiv="Content-Language" content="el">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?= CONFIG["site_title"]; ?></title>
            <link rel="shortcut icon" type="image/x-icon" href="<?= CONFIG["paths"]["img"]["layout"] . '/favicon.png'; ?>" />
            <?php
            foreach (Head_header_manager::$appended_common_head_contents as $str) {
                echo $str;
            }
            foreach (Head_header_manager::$appended_head_contents as $str) {
                echo $str;
            }
            ?>
        </head>
        <?php
        Head_header_manager::$appended_head_contents = array();
        Head_header_manager::$appended_common_head_contents = array();
    }

    public static function print_json_header()
    {
        header('Content-Type: application/json; charset=utf-8');
    }
}
