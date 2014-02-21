<?php

    $form->inputText( 'alias', 'Псевдоним' );
    $form->checkbox( 'isxml', 'Загрузить XML','1' );
    $form->inputText( 'xml', 'Название файла xml в папке media' );
    $form->checkbox( 'truncate', 'Очистить базу перед заливкой','1' );
?>