<?php

    if ( isset($template->get->id) )
    {
        $data = $module->admin->getParseById( $template->get->id->int() );
    }
    else
    {
        $data = null;
    }

    $this->setTitle( isset($template->get->id) ? 'Редактировать' : 'Добавить' );
    
    $form = new TForm( $data );
    $form->beginForm();
    
    $form->inputText( 'content',     'Контент' );
    $form->inputText( 'title',       'Заголовок' );
    $form->inputText( 'link',        'Ссылка' );
    $form->inputText( 'img',         'Картинка' );
    $form->inputText( 'description', 'Описание' );
    $form->inputText( 'text',        'Полная новость' );
    $form->inputText( 'site',        'Сайт' );
    
    $form->beginControlGroup();
    $form->checkbox( 'parser', 'Выполнять', 'on' );
    $form->endControlGroup();
    
    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    function d_complete()
    {
        $('#myModal').html( '<p>Загрузка</p>' );
        $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=parser'; ?>" );
    };
</script>
