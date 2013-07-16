<?php

    if ( isset($_GET['template']) && isset($_GET['style']) )
    {
        header( 'Content-Type: text/css' );
        header( 'X-Content-Type-Options: nosniff' );

        if ( $_GET['template'] == 'admin' )
        {
            if ( file_exists( ADMIN_DIR.'/style/'.$_GET['style'].'.css' ) )
            {
                include_once( ADMIN_DIR.'/style/'.$_GET['style'].'.css' );
            }
            else
            {
                header( 'HTTP/1.1 404 Not Found' );
            }
        }
        else
        {
            if ( file_exists( TEMP_DIR.$_GET['template'].'/style/'.$_GET['style'].'.css' ) )
            {
                include_once( TEMP_DIR.$_GET['template'].'/style/'.$_GET['style'].'.css' );
            }
            else
            {
                header( 'HTTP/1.1 404 Not Found' );
            }
        }
        exit;
    }

?>