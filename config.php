<?php

$root='foe2';//emplacement du site sur le serveur
$host='localhost';// adresse du site 127.0.0.1
$dbname='foe2'; // nom de la base de données
$password=''; // mdp de la base
$config=true;

define('DIR_ROOT',$_SERVER['DOCUMENT_ROOT'].'/'.$root.'/');
define('URL_ROOT','http://'.$_SERVER['HTTP_HOST'].'/'.$root.'/');
define('HEAD',DIR_ROOT.'inc/head.php');
define('HEADER',DIR_ROOT.'inc/header.php');
define('CONNECT',DIR_ROOT.'connect.php');
define('FOOTER',DIR_ROOT.'inc/footer.php');
define('CHARSET','content-type: text/html; charset=utf-8');
define('ERROR_AUTH','Erreur d\'authentification');
define('ERROR_DATA','Erreur de récupération des données.');
define('GUILD_AUTH','Vous devez attendre qu\'un admin valide votre accès.');


ini_set('display_errors', '1');
ini_set('log_errors',1);
ini_set('error_log',DIR_ROOT.'log_error_php.txt');