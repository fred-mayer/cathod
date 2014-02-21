<?php
    $form->checkbox( 'isHTML',"Убрать редактор?","1" );
    $form->textarea( 'html');
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
?>