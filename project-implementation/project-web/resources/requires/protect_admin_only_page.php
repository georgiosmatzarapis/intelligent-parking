<?php
//Make sure session_start is called!
if(!(isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == true)){
    header("location:login.php");
    exit; //not realy needed
}