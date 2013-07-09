<?php
	$module->admin->hideOffer($template->get->id); //получаем список категорий
	
?>
<script>
    $('#myModal').modal( 'hide' );
    location.reload(); //перезагрузка страницы fix!!!
</script>