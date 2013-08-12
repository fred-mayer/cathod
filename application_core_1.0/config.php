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
    
    //позиции
    define('POS','');

    // mysql
    //define( 'DB_NAME',      'pricezilla_new' );
    define( 'DB_NAME',      'myshop' );
    define( 'DB_USER',      'root' );
    define( 'DB_PASSWORD',  '' );
    define( 'DB_LOCATION',  'localhost' );

    define( 'LANGUAGE', 'ru' );

?>
