<?php 
session_start();
if(isset($_SESSION['temp']))
{
	unset($_SESSION['temp']);
}