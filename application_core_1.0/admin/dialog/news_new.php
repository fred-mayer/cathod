<?php

    $form->inputText( 'limit', 'Кол-во новостей' );
    $form->beginControlGroup();
    $form->checkbox( 'img', 'скрыть изображение', 'hide' );
    $form->endControlGroup();

?>