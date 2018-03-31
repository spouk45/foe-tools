<?php 
include('../config.php');

session_start();
$_SESSION=array();
session_destroy();
header('location:'.URL_ROOT.'index.php');
