<?php

    $this->setTitle( '' );

    $form = new TForm( array('user'=>'women') );
    $form->beginForm( 'collect_mail' );
    
    $form->insertField( 'email', 'email', 'Введите Ваш email', '', 'email', true );
    
    $form->beginControlGroup( 'Укажите Ваш пол' );
    $form->radio( 'user', ' Мужской', 'men' );
    $form->radio( 'user', ' Женский', 'women' );
    $form->endControlGroup();
    
    $form->submit( 'Готово' );
    
    $form->endForm();

    $this->setBody( $form );
    
    //$this->setNameButtonPrimary( 'Готово', $this->urlAction( $template ) );

    $this->displayDialog( false );
?>
<script>
    function d_get_post()
    {
        
    }
    function d_complete()
    {
        
    }
    $("#collect_mail").bind("submit", function(e){

        var email = $('#email').val();
        var user = $('.user:radio:checked').val();

        $.post('/ajax/collect_mail?action=submit', {'email':email, 'user':user}, function(data){
            
            alert(data);
        });
        $('#myModal').modal( 'hide' );

        e.preventDefault();
        return false;
    });
</script>