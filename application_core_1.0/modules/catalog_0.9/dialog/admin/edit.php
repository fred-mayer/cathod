<?php

    $tree = $module->admin->getTree("-");

    $this->setTitle( 'Редактировать Каталог товаров' );

    $form = new TForm();
    $form->beginForm();
    $form->select( 'category',"Выберите категорию отображения тваров",$tree );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template,'',true ) );
    $this->displayDialog();
    
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );

        var btn_toolbar = $(".content[idcontent=<?php echo $template->get->id; ?>] .btn-toolbar").clone(true);
        $(".content[idcontent=<?php echo $template->get->id; ?>]").html( content.getData() );
            
        btn_toolbar.prependTo(".content[idcontent=<?php echo $template->get->id; ?>]");
    };
</script>
