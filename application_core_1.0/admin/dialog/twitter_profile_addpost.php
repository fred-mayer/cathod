<?php

    $this->setTitle( 'Добавить' );

    $form = new TForm();
    $form->beginForm();
    $form->ckeditor( 'post' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', '/ajax/admin/'.$template->route[0].'?action='.$template->get->dialog.'&idprofile='.$template->get->idprofile );
    $this->displayDialog();
    
?>
<script>
    function d_complete(data)
    {
        //alert(data);

        $('#myModal').modal( 'hide' );
        $("#twitter").prepend("<li><p>"+tinymce.EditorManager.activeEditor.getContent()+"</p></li>");
    };
</script>
