<?php

/* ----------------------------
 * Here is the Front Controller
 * Reveal errors and exceptions
 * Guest Initialize
 * Time Zone for Db Models
 * Define global root dir
 * Launch the router
 */

ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set( 'Europe/Warsaw');
define('ROOT', __DIR__ . '/..');
require_once(ROOT . '/routes/Router.php');
require_once(ROOT . '/helpers/loader.php');
$r = new Router();
$r->run();
