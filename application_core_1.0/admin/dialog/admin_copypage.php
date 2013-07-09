<?php

    $data = $module->getPage( $template->get->idpage );
    $temp = $module->getTemplats();

    $this->setTitle( 'Клонировать Страницу' );
    
    $form = new TForm( $data );
    $form->beginForm();
    
    $form->inputText( 'title', 'Название' );
    $form->inputText( 'alias', 'Название URL' );
    $form->textarea( 'keywords', 'Ключевые слова' );
    $form->textarea( 'descripion', 'Описание' );
    $form->textarea( 'script', 'Скрипт' );
    $form->textarea( 'style', 'Стиль' );
    $form->select( 'template', 'Шаблон', $temp );
    
    $form->beginControlGroup();
    $form->checkbox( 'hide', 'скрыть из общего доступа', 'hide' );
    $form->endControlGroup();
    
    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Клонировать', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        document.location.href = "http://"+document.location.host+"/"+$('#alias').val();
    };
</script>
