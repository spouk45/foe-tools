<?php
if(!isset($_SESSION['account']))
{
	echo ('Erreur d\'authentification.');
	exit();
}

if($_SESSION['account']['name']!="spouk"){
	echo ('Erreur d\'authentification.');
	exit();
}