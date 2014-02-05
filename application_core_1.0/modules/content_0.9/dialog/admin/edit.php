<?php

    $data = $module->admin->getContent( $template->get->idmodule->int() );
    $d = json_decode($data);

    $this->setTitle( 'Редактировать контент' );

    $form = new TForm( $data );
    $form->beginForm();
    $form->checkbox( 'isHTML',"Убрать редактор?","1" );
    $form->textarea( 'html','',$d->content );
    $form->ckeditor( 'content' );
    $form->addScript( '
    	$("#html").addClass("sourceEditor").hide();
    	$("#isHTML").click(function(){
    		if($(this).is(":checked")){
    			$(".mce-tinymce").hide();
    			$("#html").show();
    		}else{
    			$(".mce-tinymce").show();
    			$("#html").hide();
    		}
    	});
    ' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
    $this->displayDialog();
    
?>
<script>
    function d_complete(data)
    {
        $('#myModal').modal( 'hide' );

        var btn_toolbar = $("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>] .btn-toolbar").clone(true);
        $("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>]").html();
        //location.reload();
        btn_toolbar.prependTo("div.content[idmodule=<?php echo $template->get->idmodule->int(); ?>]");
    };
</script>
