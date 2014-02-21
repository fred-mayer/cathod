<?php
    if(!defined("APPLICATION_CORE")){ header("HTTP/1.0 404 Not Found"); die("access denied!");}
    define( 'SERVER_NAME', 'http://'.$_SERVER["SERVER_NAME"].'' );
    define( 'CURRENT_URL', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'] );
    
    //folders
    define('ADMIN_DIR',     APPLICATION_CORE.'/admin/');
    define('CLASS_DIR',     APPLICATION_CORE.'/class/');
    define('MODULES_DIR',   'modules/');
    define('PAGES_DIR',     APPLICATION_CORE.'/pages/');
    define('TEMP_DIR',      'templates/');
    define('DIALOG_DIR',    APPLICATION_CORE.'/dialog/');

    define('TMP_DIR',       'tmp/');
	define('DS',       '/');
    
    //позиции
    define('POS','');
    
    //шаблон
    define('TEMPLATE','temp1');

    // mysql
    define( 'DB_NAME',      'cathod' );
    define( 'DB_USER',      'root' );
    define( 'DB_PASSWORD',  'root' );
    define( 'DB_LOCATION',  'localhost' );

    define( 'LANGUAGE', 'ru' );
?>
