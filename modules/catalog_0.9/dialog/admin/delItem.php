<?php

$item = $module->admin->getItem($template->get->id);

    $this->setTitle( 'Удаоить товар '.$item->name. '?' );

    $form = new TForm();
    $form->beginForm();
    $form->html( '<p class="lead text-warning">Вы действительно хотите удалить товар "'.$item->name.'"?</p>' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Точно удалить!', $this->urlAction( $template,'',true ) );
    $this->displayDialog(); 
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        
        $(".product[idproduct=<?php echo $template->get->id; ?>]").parent('.span2').remove();
    };
</script>