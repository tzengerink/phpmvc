<?php

/* -- PROFILING ------------------------------------------------------------ */

define('START_TIME', microtime(TRUE));
define('START_MEMORY', memory_get_usage());

/* -- ENVIRONMENT SETUP & SYSTEM CONFIGURATION ----------------------------- */

// Set DOCROOT & APPROOT
define('DOCROOT', realpath(dirname(__FILE__)).'/');
define('APPROOT', DOCROOT.'application/');

// Require Core class
require_once(APPROOT.'classes/core.php');

// Register Core auto loader
spl_autoload_register(array('Core', 'auto_load'));

// Register Core exception handler
set_exception_handler(array('Core', 'exception_handler'));

// Set BASEURL constant
define('BASEURL', 'http://'.$_SERVER['HTTP_HOST'].Config::load('core')->get('webroot'));

// Set error display & reporting level
ini_set('display_errors', Config::load('core')->get('is_production') ? 'off' : 'on');
if (Config::load('core')->get('is_production'))
	error_reporting(E_ALL ^ E_NOTICE);
else
	error_reporting(E_ALL | E_STRICT);

// Set language
I18n::lang(Config::load('core')->get('language'));

// Set timezone
date_default_timezone_set(Config::load('core')->get('timezone'));

/* -- EXECUTE REQUEST ------------------------------------------------------ */

// Create and execute the request
Request::instance()->execute();
