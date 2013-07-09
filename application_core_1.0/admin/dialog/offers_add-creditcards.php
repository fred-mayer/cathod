<?php
	$banks = $module->admin->getAllBanks();
    $id = $template->get->id;
    $offer = $module->admin->getOffer($id);

    $dialog->setTitle( $offer['name'] . ' - добавление параметров' );
    ob_start();
    ?>
    <form class="form-horizontal">
    	<div class="control-group">
    		<label class="control-label" for="inputName">Название:</label>
    		<div class="controls">
				<input type="text" id="inputName" value="<? echo $offer['name'] ?>" />
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Банк:</label>
    		<div class="controls">
				<select id="banks">
					<? foreach($banks as $bank){ ?>
					<option value="<? echo $bank->id ?>" <? echo ($bank->id==$offer['bank_id'])? 'selected="selected"':'' ?>><? echo $bank->bank_name ?></option>
					<? } ?>
				</select>
			</div>
    	</div>
    	<div class="control-group">
    		 <label class="checkbox">
				<input type="checkbox" id="published" <? if($offer['published']==0){ echo "checked"; } ?>> Скрыто			
			</label>
    	</div>
    	<hr/>
	    	<div class="control-group">
	    		<label class="control-label">Мин. ставка:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="min_rate" value="" /><span class="add-on">%</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Макс. ставка:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="max_rate" value="" /><span class="add-on">%</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Кредитный лимит:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="limit" value="" /><span class="add-on">руб.</span>
	    			</div>
				</div>
	    	</div>
	    	
	    	<div class="control-group">
	    		<label class="control-label">Льготный период:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="grace_period" value="" /> <span class="add-on">дней.</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Рассмотрение заявки:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="-1" /> нет 
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="0" /> День в день
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="1" /> За 1 день
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="2" /> За 2 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="3" /> За 3 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="4" /> За 4 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="5" /> За 5 дней
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Подтверждение дохода:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="-1" /> не показывать 
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="0" /> нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="1" /> да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Необходимые документы:</label>
	    		<div class="controls">
	    					<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="1"> другие документы, кроме справок о доходах;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="2"> справка 2-НДФЛ;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="3"> справка от работодателя по форме банка / в свободной форме;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="4"> косвенное подтверждение доходов;
							</label>
	    				
				</div>
	    	</div>
    </form>
    <?
    $html = ob_get_contents();
    ob_end_clean();
    $dialog->setBody( $html );
    $dialog->setNameButtonPrimary( 'Сохранить' );

    $dialog->displayDialog();


    $url = '/ajax/'.$route[0].'?action='.str_replace("-","_",$template->get->dialog).'&id='.$template->get->id;
?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var name = $('#inputName').val();
        var bank = $('#banks').val();
        var urlForm = $('#urlForm').val();
        if($('#published').is(':checked')){
	        var published = 0;
        }else{ var published = 1; }
        
        var minRate = $('#min_rate').val(); //все
        var maxRate = $('#max_rate').val(); //все
        
	    var limit = $('#limit').val();
	    var gracePeriod = $('#grace_period').val();

        var timeToConsider = $('.time_to_consider:checked').val();
        var approvDocs = $('.approv_docs:checked').val();
        var approvNeeded = $('.approv_needed:checked').val();
        var approvDocs = [];
         $('.approv_docs').each(function(i,elem) {
			if($(this).prop("checked")){
				approvDocs.push($(this).val());
			}
		});
        $.post('<?php echo $url; ?>', {"name":name,"bank":bank,
	        "urlForm":urlForm,"published":published,
	        
		    "limit":limit,
		    "grace_period":gracePeriod,
	
	        "time_to_consider":timeToConsider,
	        "approvement_documents":approvDocs,
	        "approv_needed":approvNeeded,
	        
	        "min_rate":minRate,
	        "max_rate":maxRate    
        }, function(data){    
            $('#myModal').modal( 'hide' );
            location.reload(); //fix!!!
        });

        e.preventDefault();
        return false;
    });
</script>