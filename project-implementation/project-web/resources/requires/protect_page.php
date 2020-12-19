<?php
//Make sure session_start is called!
if(!isset($_SESSION['username'])){
    header("location:login.php");
}