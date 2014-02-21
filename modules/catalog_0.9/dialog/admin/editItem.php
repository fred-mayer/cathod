<?php

    $tree = $module->admin->getTree("-");
    $item = $module->admin->getItem($template->get->id);

    $this->setTitle( 'Редактировать товар '.$item->name );

    $form = new TForm();
    $form->beginForm();
    $form->select( 'category',"Выберите категорию товара",$tree );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template,'',true ) );
    $this->displayDialog(); 
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        
        $(".product[idproduct=<?php echo $template->get->id; ?>]").parent('.span2').remove();
    };
</script>