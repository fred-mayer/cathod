<?php

    $data = $module->admin->getContent( $template->get->idmodule->int() );

    $this->setTitle( 'Редактировать контент' );

    $form = new TForm( $data );
    $form->beginForm();
    $form->ckeditor( 'content' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();
    
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );

        var btn_toolbar = $("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>] .btn-toolbar").clone(true);
        
        $("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>]").html( tinymce.EditorManager.activeEditor.getContent() );
            
        btn_toolbar.prependTo("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>]");
    };
</script>
