<?php

    $data = $module->admin->get( $template->get->idmodule->int() );

    $this->setTitle( 'Редактировать модуль новости' );

    $form = new TForm( $data );
    $form->beginForm();
    $form->inputText( 'limit', 'Кол-во новостей' );
    $form->beginControlGroup();
    $form->checkbox( 'img', 'скрыть изображение', 'hide' );
    $form->endControlGroup();
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();
    
?>
<script>
    function d_complete(data)
    {
        $('#myModal').modal( 'hide' );

        var btn_toolbar = $("div.news[idmodule=<?php echo $template->get->idmodule; ?>] .btn-toolbar").clone(true);
        $("div.news[idmodule=<?php echo $template->get->idmodule; ?>]").html( data );
            
        btn_toolbar.prependTo("div.news[idmodule=<?php echo $template->get->idmodule; ?>]");
    };
</script>
