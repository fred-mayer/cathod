<?php

    $data = $module->admin->getpost( $template->get->idpost->int() );

    $this->setTitle( 'Редактировать' );

    $form = new TForm( $data );
    $form->beginForm();
    $form->ckeditor( 'post' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', '/ajax/admin/'.$template->route[0].'?action='.$template->get->dialog.'&idpost='.$template->get->idpost );
    $this->displayDialog();
    
?>
<script>
    function d_complete(data)
    {
        //alert(data);

        $('#myModal').modal( 'hide' );
        var li = $(".btn[action=editpost][idpost=<?php echo $template->get->idpost; ?>]").parent().parent().parent();
        
        var btn_toolbar = li.find(".btn-toolbar").clone(true);
        
        li.html('<img src="/media/images/point.png" alt="">');
        li.append(btn_toolbar);
        li.append('<p>'+tinymce.EditorManager.activeEditor.getContent()+'<p>');
    };
</script>
