<?php
	$cats = $module->admin->getCategories(); //получаем список категорий
	
    //$data = $module->admin->getOffer( $ajax->db, $ajax->get->id ); //получаем данные о предложении
    $banks = $module->admin->getAllBanks();
    $cat = $template->get->id;

    $dialog->setTitle( 'Добавить предложение' );
    ob_start();
    ?>
    <form class="form-horizontal">
    	<div class="control-group">
    		<label class="control-label" for="inputName">Название:</label>
    		<div class="controls">
				<input type="text" id="inputName" value="" />
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Категория:</label>
    		<div class="controls">
				<select id="cats">
					<? foreach($cats['obj'] as $catobj){ ?>
					<option value="<? echo $catobj->cat_name ?>" <? if($cat==$catobj){ echo 'checked';} ?>><? echo $catobj->cat_title ?></option>
					<? } ?>
				</select>
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Банк:</label>
    		<div class="controls">
				<select id="banks">
					<? foreach($banks as $bank){ ?>
					<option value="<? echo $bank->id ?>"><? echo $bank->bank_name ?></option>
					<? } ?>
				</select>
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Изображение:</label>
    		<div class="controls">
				<input type="text" id="inputImage" value="" />
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Описание:</label>
    		<div class="controls">
    			<textarea id="inputDescription"></textarea>
				
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Треккинг ссылка:</label>
    		<div class="controls">
				<input type="text" id="urlForm" value="" />
			</div>
    	</div>
    	<div class="control-group">
    		 <label class="checkbox">
				<input type="checkbox" id="published" checked> Скрыто			
			</label>
    	</div>
    	<hr/>
    	
    </form>
    <?
    $html = ob_get_contents();
    ob_end_clean();
    $dialog->setBody( $html );
    $dialog->setNameButtonPrimary( 'Далее' );

    $dialog->displayDialog();


    $url = '/ajax/'.$route[0].'?action='.$template->get->dialog.'&cat='.$template->get->id;


?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var name = $('#inputName').val();
        var bank = $('#banks').val();
        var image = $('#inputImage').val();
        var descr = $('#inputDescription').val();
        var cat = $('#cats').val();
        var urlForm = $('#urlForm').val();
        if($('#published').is(':checked')){
	        var published = 0;
        }else{ var published = 1; }
        
        $('#myModal').html( '<p>Загрузка</p>' );
        
        $.getJSON('<?php echo $url; ?>', {
	        "name":name,"cat":cat,"bank":bank,"image":image,
	        "descr":descr,"urlForm":urlForm,"published":published
        }).done(function( json ) {
            // Добавление лиалогового окна - fix!!! - сделать общую функцию открытия окна!
		    $('#myModal').load('/ajax/offers?dialog=add-'+cat+'&id='+json[0]);
        });

        e.preventDefault();
        return false;
    });
</script>