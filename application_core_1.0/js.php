<?php

    if ( isset($_GET['template']) && isset($_GET['js']) )
    {
        header( 'Content-type: text/javascript' );
        
        if ( $_GET['template'] == 'admin' )
        {
            if ( file_exists( ADMIN_DIR.'/js/'.$_GET['js'].'.php' ) )
            {
                include_once( ADMIN_DIR.'/js/'.$_GET['js'].'.php' );
            }
            else
            {
                header( 'HTTP/1.1 404 Not Found' );
            }
        }
        else
        {
            if ( file_exists( TEMP_DIR.$_GET['template'].'/js/'.$_GET['js'].'.js' ) )
            {
                include_once( TEMP_DIR.$_GET['template'].'/js/'.$_GET['js'].'.js' );
            }
            else
            {
                header( 'HTTP/1.1 404 Not Found' );
            }
        }
        exit;
    }

?>