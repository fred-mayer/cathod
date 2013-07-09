<?php

    $data = $module->admin->getContent( $template->get->id->int() );

    $dialog->setTitle( 'Редактировать контент' );
    $dialog->setBody( '<textarea id="ckeditor" rows="10" cols="45" name="text">'.$data['content'].'</textarea>' );
    $dialog->setNameButtonPrimary( 'Сохранить' );

    $dialog->displayDialog();
    
    $url = '/ajax/'.$route[0].'?action='.$template->get->dialog.'&id='.$template->get->id;

?>
<script>
    var editor = CKEDITOR.replace( 'ckeditor' );
    
    
    $(".modal-footer .btn-primary").bind("click", function(e){

        var content = editor.getData();
        $.post('<?php echo $url; ?>', {"content":content}, function(data){
            
            $('#myModal').modal( 'hide' );

            // data - содержит HTML модуля
        });

        e.preventDefault();
        return false;
    });
</script>