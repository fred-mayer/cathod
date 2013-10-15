<?php


    set_time_limit( 14400 );

    $catalog = $template->getModule( 'catalog' );

    if ( isset($template->get->skey) && $template->get->skey == '1q2w3e4r5t' && isset($template->get->mag) )
    {
        $catalog->parser($template->get->mag);
    }

    exit();

?>