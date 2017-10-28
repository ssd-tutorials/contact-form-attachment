<?php
date_default_timezone_set('Europe/London');

defined("DS")
	|| define("DS", DIRECTORY_SEPARATOR);
	
defined("ROOT_PATH")
	|| define("ROOT_PATH", realpath(dirname(__FILE__) . DS.'..'.DS));
	
defined("CLASSES_PATH")
	|| define("CLASSES_PATH", ROOT_PATH.DS.'classes');
	
defined("UPLOAD_PATH")
	|| define("UPLOAD_PATH", ROOT_PATH.DS.'uploads');
	
defined("EMAILS_PATH")
	|| define("EMAILS_PATH", ROOT_PATH.DS.'emails');
	
defined("MAX_SIZE")
	|| define("MAX_SIZE", 1048576);
	
defined("FILE_EXT")
	|| define("FILE_EXT", 'dat');
	
	
	
defined("SMTP_HOST")
	|| define("SMTP_HOST", 'mail.somedomain.com');
	
defined("SMTP_AUTHENTICATION")
	|| define("SMTP_AUTHENTICATION", true);
	
defined("SMTP_PORT")
	|| define("SMTP_PORT", 25);
	
defined("SMTP_USERNAME")
	|| define("SMTP_USERNAME", 'smtp@somedomain.com');
	
defined("SMTP_PASSWORD")
	|| define("SMTP_PASSWORD", 'password');
	
defined("SMTP_ENCRYPTION")
	|| define("SMTP_ENCRYPTION", '');
	
	
	

defined("EMAIL_TO")
	|| define("EMAIL_TO", 'myemail@somedomain.com');
	
defined("NAME_TO")
	|| define("NAME_TO", 'My name');
	
	

	
require_once(CLASSES_PATH.DS.'Autoloader.php');

spl_autoload_register(array('Autoloader', 'load'));
	
	
	
	
	
	
	
	
	