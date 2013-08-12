<?php

    //$tree = $module->admin->getTree("-");

    $this->setTitle( 'Парсинг товаров с магазинов' );

    ob_start();
	$module->admin->parseHTML();
	$html = ob_get_contents();
    ob_end_clean();

    $this->setBody( $html );
    $this->setNameButtonPrimary( 'ОК', $this->urlAction( $template,'',true ) );
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
