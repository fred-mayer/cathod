<?php

    $temp = $module->getTemplats();

    $this->setTitle( 'Новая Страница' );
    
    $form = new TForm();
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
    
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    var alias;
    
    function d_start()
    {
        alias = $('#alias').val();
    };
    
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        document.location.href = "http://"+document.location.host+"/"+alias;
    };
</script>
