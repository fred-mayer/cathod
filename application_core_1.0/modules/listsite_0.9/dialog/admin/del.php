<?php

    $this->setTitle( 'Уверины что хотите удалить?' );
    

    //$this->setBody( $form );
    $this->setNameButtonPrimary( 'Удалить', $this->urlAction( $template, '', true ) );
    $this->displayDialog();

?>
<script>
    function d_get_post()
    {

    };
    function d_complete(data)
    {
        //alert( data );

        $('#myModal').modal( 'hide' );
        $("a.btn[idmodule='<?php echo $template->get->idmodule; ?>'][module='listsite']").parent().parent().parent().remove();
    };
</script>
