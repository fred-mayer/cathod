<?php

    $this->setTitle( 'Уверины что хотите удалить профиль?' );

    $form = new TForm();
    $form->beginForm();
    $form->hidden( 'idprofile', $template->get->idprofile );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Удалить', '/ajax/admin/'.$template->route[0].'?action='.$template->get->dialog.'&idprofile='.$template->get->idprofile );
    $this->displayDialog();

?>
<script>
    function d_complete(data)
    {
        //alert( data );

        location.replace('<?php echo 'http://'.$_SERVER["SERVER_NAME"].'/profile/'; ?>');
    };
</script>
