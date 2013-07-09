<?php

    $template->setTitle( 'Login' );
    $template->setStyle( 'style.css' );

    
    if ( !$template->auth->isAuthorized )
    {
        $post = new TMethod( $_POST );
        
        if ( isset($post->login) && isset($post->password) ) // Если нет авторизации, то проверяем пришол POST запрс
        {
            if ( $template->auth->login( $post->login, $post->password ) )
            {
                // переадресация на главную
                $template->location();
            }
            else
            {
                // Не верный логин или пароль
                $error = 'Не верный логин или пароль';
            }
        }
    }
    else
    {
        // ПОЛЬЗОВАТЕЛЬ АВТОРИЗОВАН
        $template->location();
    }


    $login = $template->getModule( 'login', (isset($error) ? array( 'error'=>$error, 'login'=>$post->login ) : '') );

    
    $template->setPos( 'section', $login );

?>