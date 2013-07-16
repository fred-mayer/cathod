<?php
	//$module->admin->deleteOffer($template->get->id); //получаем список категорий
	$dialog->setTitle('Удаление предложения' );
	ob_start();
?>
    <div class="alert alert-info">
	    Подтвердите удаление этого предложения!
    </div>
<?
	$html = ob_get_contents();
    ob_end_clean();
    $dialog->setBody( $html );
    $dialog->setNameButtonPrimary( 'Удалить' );
    $dialog->displayDialog();
    $url = '/ajax/'.$route[0].'?action='.$template->get->dialog.'&id='.$template->get->id;
    
?>
<script>
	$(".modal-footer .btn-primary").bind("click", function(e){
		$.post('<?php echo $url; ?>', {"id":<? echo $template->get->id ?> }, function(data){
	            
	            $('#myModal').modal( 'hide' );
	            location.reload(); //перезагрузка страницы fix!!!
	
	    });
     e.preventDefault();
        return false;
    });
</script>