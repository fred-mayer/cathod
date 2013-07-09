<?php

    if ( isset($_GET['template']) && isset($_GET['img']) && isset( $_GET['type'] ) )
    {
        if ( $_GET['type'] == 'gif' )
            header( 'Content-Type: image/gif' );
        elseif ( $_GET['type'] == 'jpg' )
            header( 'Content-Type: image/jpeg' );
        elseif ( $_GET['type'] == 'png' )
            header( 'Content-Type: image/png' );
        elseif ( $_GET['type'] == 'ico' )
            header( 'Content-Type: image/x-icon' );
        else
        {
            header( 'HTTP/1.1 404 Not Found' );
            exit;
        }

        
        if ( file_exists( TEMP_DIR.$_GET['template'].'/img/'.$_GET['img'].'.'.$_GET['type'] ) )
        {
            readfile( TEMP_DIR.$_GET['template'].'/img/'.$_GET['img'].'.'.$_GET['type'] );
        }
        else
        {
            header( 'HTTP/1.1 404 Not Found' );
        }
        exit;
    }

?>