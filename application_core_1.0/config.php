<?php

    define( 'SERVER_NAME', 'http://'.$_SERVER["SERVER_NAME"].'' );
    define( 'CURRENT_URL', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'] );
    
    //folders
    define('ADMIN_DIR',     APPLICATION_CORE.'/admin/');
    define('CLASS_DIR',     APPLICATION_CORE.'/class/');
    define('MODULES_DIR',   APPLICATION_CORE.'/modules/');
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
    define( 'DB_NAME',      'db' );
    define( 'DB_USER',      'user' );
    define( 'DB_PASSWORD',  'pass' );
    define( 'DB_LOCATION',  'localhost' );

    define( 'LANGUAGE', 'ru' );

?>
