<?php

    if ( isset($template->get->id) )
    {
        $data = $module->admin->getFormFieldById( $template->get->id->int() );
    }
    else
    {
        $data = null;
    }

    $this->setTitle( isset($template->get->id) ? 'Редактировать' : 'Добавить' );
    
    $form = new TForm( $data );
    $form->beginForm();
    
    $option = array( array('title'=>'Строка', 'value'=>'inputText'), 
        array('title'=>'Текст', 'value'=>'textarea'), 
        array('title'=>'Адрес электронной почты', 'value'=>'email'), 
        array('title'=>'Телефонный номер', 'value'=>'phone'), 
        array('title'=>'Файл', 'value'=>'file'),
        array('title'=>'Список', 'value'=>'select') );

    if ( isset($template->get->id) )
    {
        $form->hidden( 'id', $template->get->id );
    }
    $form->hidden( 'id_form', $template->get->id_form );
    $form->inputText( 'name',           'Название поля' );
    $form->inputText( 'label',          'Метка' );
    $form->inputText( 'placeholder',    'Текст внутри поля' );
    $form->select( 'type',              'Тип поля', $option, $data == null ? '' : $data->type );
    $form->checkbox( 'is_required',     'Обезательное заполнение', 'yes' );
    $form->inputText( 'pattern',        'Регулярное выражение' );
    $form->inputText( 'order',          'Позиция' );

    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template, 'insert_fields', true ) );
    $this->displayDialog();

?>
<script>
    function d_complete(data)
    {
        //alert(data);

        $('#myModal').html( '<p>Загрузка</p>' );
        $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit&idmodule='.$template->get->idmodule; ?>" );
    };
</script>
