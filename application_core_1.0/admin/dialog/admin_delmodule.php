<?php

    $this->setTitle( 'Уверины что хотите удалить модуль?' );
    
    $form = new TForm();
    $form->beginForm();
    $form->checkbox( 'pages', 'удалить модуль на всех страницах', 'all' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Удалить', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    function d_complete(data)
    {
        //alert( data );

        $('#myModal').modal( 'hide' );
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>']").remove();
    };
</script>
