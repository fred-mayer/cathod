<?php

    include_once( 'img.php' );
    include_once( 'js.php' );
    include_once( 'style.php' );


    // Автоматом подгружаем все классы
    $files = scandir( CLASS_DIR );

    foreach ( $files as $file )
    {
    	$exp = explode('.', $file);
        $ext = strtolower(end($exp)); //вырезаем расширение у файла
        if($ext=="php")
            if ( is_file( CLASS_DIR.$file ) ) include_once( CLASS_DIR.$file );
    }



    $template = new TTemplate;

    $page = $template->route->loadCurrentPage(); // Идет подгрузка контроллера-страницы


    if ( isset($template->get->ajax) )
    {
        $page->ajax( $template );
    }
    elseif ( isset($template->get->admin) )
    {
        $page->admin( $template );
    }
    else
    {
        $page->display( $template );
    }

?>