<?php

    if ( isset($_GET['ajax']) )
    {
        /*include_once( CLASS_DIR.'dialog.php' );
        include_once( CLASS_DIR.'form.php' );*/
        

        $template = new TTemplate( 'temp1' ); // ??????? 'temp1'
        //$module = new TModule( $template );


        // Идет подгрузка модуля  
        $module = $template->route->loadCurrentModule( $template );

        if ( isset($template->get->idpage) )
            $template->idpage = $template->get->idpage->int();


        $dialog = new TDialog;
        
        if ( $template->auth->isAuthorized ) // Если авторизованы товыполняем скрипт
        {
            if ( !$dialog->loadCurrentDialog( $template, $module ) )
            {
                // Диолог не был загружен то попробуем выполнить действие
                if ( !$module->action() ) // выполняем действие
                {
                    echo 'error get';
                }
            }
        }
        else
        {
            echo 'error auth';
        }
        exit;
    }

?>