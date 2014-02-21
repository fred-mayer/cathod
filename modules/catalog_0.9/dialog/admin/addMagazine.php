<?php

    //$tree = $module->admin->getTree("-");

    $this->setTitle( 'Добавление магазина для парсинга' );

    $form = new TForm();
    $form->beginForm();
    $form->inputText( 'name',"Название магазина");
	$form->inputText( 'url',"Ссылка на сайт магазина");
	$form->inputText( 'trekking_url',"Треккинг ссылка");
	$form->inputText( 'logo',"Логотип");
	$form->inputText( 'script_parser',"Имя скрипта-парсера для магазина");
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
