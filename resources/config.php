<?php

ob_start();
session_start();
// session_destroy();

// DS means "/"
defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

//echo __DIR__;
//	http://localhost/ecom/resources/config.php
// /Applications/XAMPP/xamppfiles/htdocs/ecom/resources


defined("TEMPLATES_FRONT") ? null : define("TEMPLATES_FRONT", __DIR__ . DS . "templates/front");

defined("TEMPLATES_BACK") ? null : define("TEMPLATES_BACK", __DIR__ . DS . "templates/back");

//echo TEMPLATES_FRONT;	
// /Applications/XAMPP/xamppfiles/htdocs/ecom/resources/templates/front

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");



// localhost setting
defined("DB_HOST") ? null : define("DB_HOST", "localhost");

defined("DB_USER") ? null : define("DB_USER", "root");

defined("DB_PASS") ? null : define("DB_PASS", "password");

defined("DB_NAME") ? null : define("DB_NAME", "ecom_db");
// end of localhost setting



$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once('functions.php');
require_once('cart.php');

?>