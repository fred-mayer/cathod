<?php
	$cats = $module->admin->getCategories(); //получаем список категорий
	
    $data = $module->admin->getOffer($template->get->id->int() ); //получаем данные о предложении
    $banks = $module->admin->getAllBanks();

    $dialog->setTitle( 'Редактировать предложение - '.$data['name'] );
    ob_start();
    ?>
    <form class="form-horizontal">
    	<div class="control-group">
    		<label class="control-label" for="inputName">Название:</label>
    		<div class="controls">
				<input type="text" id="inputName" value="<? echo $data['name'] ?>" />
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Категория:</label>
    		<div class="controls">
				<select id="cats">
					<? foreach($cats['obj'] as $catobj){ ?>
					<option value="<? echo $catobj->cat_name ?>" <? echo ($data['cat_name']==$catobj->cat_name)? 'selected="selected"':'' ?>><? echo $catobj->cat_title ?></option>
					<? } ?>
				</select>
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Банк:</label>
    		<div class="controls">
				<select id="banks">
					<? foreach($banks as $bank){ ?>
					<option value="<? echo $bank->id ?>" <? echo ($bank->id==$data['bank_id'])? 'selected="selected"':'' ?>><? echo $bank->bank_name ?></option>
					<? } ?>
				</select>
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Изображение:</label>
    		<div class="controls">
				<input type="text" id="inputImage" value="<? echo $data['images'] ?>" />
			</div>
    	</div>
    	<div class="control-group">
    		<label class="control-label">Описание:</label>
    		<div class="controls">
    			<textarea id="inputDescription"><? echo $data['descr'] ?></textarea>
				
			</div>
    	</div>
    	<hr/>
    	
	    	<div class="control-group">
	    		<label class="control-label">Мин. ставка:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="min_rate" value="<? echo $data['min_rate'] ?>" /><span class="add-on">%</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Макс. ставка:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="max_rate" value="<? echo $data['max_rate'] ?>" /><span class="add-on">%</span>
	    			</div>
				</div>
	    	</div>
	    	<? if($data['cat_name']=='creditcards'){ //данные для кредитных карт ?> 
	    	<div class="control-group">
	    		<label class="control-label">Кредитный лимит:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="limit" value="<? echo $data['limit'] ?>" /><span class="add-on">руб.</span>
	    			</div>
				</div>
	    	</div>
	    	<? } ?>
	    	<? if($data['cat_name']=='credits' || $data['cat_name']=='deposits'){ //данные для кредитных карт ?> 
	    	<div class="control-group">
	    		<label class="control-label"><? echo ($data['cat_name']=='credits')? 'Макс. сумма':'Мин. сумма' ?>:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="sum" value="<? echo $data['sum'] ?>" /><span class="add-on">руб.</span>
	    			</div>
				</div>
	    	</div>
	    	<? } ?>
	    	<? if($data['cat_name']=='credits' || $data['cat_name']=='deposits'){ ?> 
	    	<div class="control-group">
	    		<label class="control-label">Макс. срок:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="period" value="<? echo $data['period'] ?>" /><span class="add-on">мес.</span>
	    			</div>
				</div>
	    	</div>
	    	<? } ?>
	    	<? if($data['cat_name']=='deposits'){  ?> 
	    	<div class="control-group">
	    		<label class="control-label">Капитализация:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="-1" <? if($data['is_capitalization']=="-1"){ echo "checked";} ?> /> На выбор вкладчика
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="0" <? if($data['is_capitalization']=="0"){ echo "checked";} ?> /> Нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="1" <? if($data['is_capitalization']=="1"){ echo "checked";} ?> /> Да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Пополнение:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="is_replenishable" name="is_replenishable" value="0" <? if($data['is_replenishable']=="0"){ echo "checked";} ?> /> Нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_replenishable" name="is_replenishable" value="1" <? if($data['is_replenishable']=="1"){ echo "checked";} ?> /> Да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Выплата %:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="1" <? if($data['capitalization_period']=="1"){ echo "checked";} ?> /> в конце срока
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="2" <? if($data['capitalization_period']=="2"){ echo "checked";} ?> /> проценты вперёд
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="3" <? if($data['capitalization_period']=="3"){ echo "checked";} ?> /> ежедневно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="4" <? if($data['capitalization_period']=="4"){ echo "checked";} ?> /> еженедельно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="5" <? if($data['capitalization_period']=="5"){ echo "checked";} ?> /> ежемесячно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="6" <? if($data['capitalization_period']=="6"){ echo "checked";} ?> /> ежеквартально
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="7" <? if($data['capitalization_period']=="7"){ echo "checked";} ?> /> раз в полгода
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="8" <? if($data['capitalization_period']=="8"){ echo "checked";} ?> /> ежегодно
	    			</label>
				</div>
	    	</div>
	    	<? } ?>
	    	<? if($data['cat_name']=='creditcards'){ //данные для кредитных карт ?> 
	    	<div class="control-group">
	    		<label class="control-label">Льготный период:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="grace_period" value="<? echo $data['grace_period'] ?>" /> <span class="add-on">дней.</span>
	    			</div>
				</div>
	    	</div>
	    	<? } ?>
	    	<? if($data['cat_name']=='creditcards' || $data['cat_name']=='credits'){ ?> 
	    	<div class="control-group">
	    		<label class="control-label">Рассмотрение заявки:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="-1" <? if($data['time_to_consider']=="-1"){ echo "checked";} ?> /> нет 
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="0" <? if($data['time_to_consider']=="0"){ echo "checked";} ?> /> День в день
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="1" <? if($data['time_to_consider']=="1"){ echo "checked";} ?> /> За 1 день
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="2" <? if($data['time_to_consider']=="2"){ echo "checked";} ?> /> За 2 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="3" <? if($data['time_to_consider']=="3"){ echo "checked";} ?> /> За 3 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="4" <? if($data['time_to_consider']=="4"){ echo "checked";} ?> /> За 4 дня
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="time_to_consider" name="time_to_consider" value="5" <? if($data['time_to_consider']=="5"){ echo "checked";} ?> /> За 5 дней
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Подтверждение дохода:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="-1" <? if($data['approv_needed']=="-1"){ echo "checked";} ?> /> не показывать 
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="0" <? if($data['approv_needed']=="0"){ echo "checked";} ?> /> нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="approv_needed" name="approv_needed" value="1" <? if($data['approv_needed']=="1"){ echo "checked";} ?> /> да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Необходимые документы:</label>
	    		<div class="controls">
	    					<? $docs = json_decode($data['approvement_documents']); 
	    						
	    						if(!is_array($docs)){$docs=array();}
	    					 ?>
	    					<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="1" <? if(array_search(1,$docs)!==false){ echo 'checked';} ?>> другие документы, кроме справок о доходах;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="2" <? if(array_search(2,$docs)!==false){ echo 'checked';} ?>> справка 2-НДФЛ;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="3" <? if(array_search(3,$docs)!==false){ echo 'checked';} ?>> справка от работодателя по форме банка / в свободной форме;
							</label>
							<label class="checkbox">
								<input type="checkbox" name="approv_docs" class="approv_docs" value="4" <? if(array_search(4,$docs)!==false){ echo 'checked';} ?>> косвенное подтверждение доходов;
							</label>
	    				
				</div>
	    	</div>
	    	<? } ?>
    </form>
    <?
    $html = ob_get_contents();
    ob_end_clean();
    $dialog->setBody( $html );
    $dialog->setNameButtonPrimary( 'Сохранить' );

    $dialog->displayDialog();


    $url = '/ajax/'.$template->route[0].'?action='.$template->get->dialog.'&id='.$template->get->id;


?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var name = $('#inputName').val();
        var bank = $('#banks').val();
        var image = $('#inputImage').val();
        var descr = $('#inputDescription').val();
        
        var minRate = $('#min_rate').val(); //все
        var maxRate = $('#max_rate').val(); //все
        <? if($data['cat_name']=='creditcards'){ ?>
	    var limit = $('#limit').val();
	    var gracePeriod = $('#grace_period').val();
        <? } ?>
        <? if($data['cat_name']=='creditcards' || $data['cat_name']=='credits'){ ?>
        var timeToConsider = $('.time_to_consider:checked').val();
        var approvDocs = $('.approv_docs:checked').val();
        var approvNeeded = $('.approv_needed:checked').val();
        var approvDocs = [];
         $('.approv_docs').each(function(i,elem) {
			if($(this).prop("checked")){
				approvDocs.push($(this).val());
			}
		});
        <? } ?>
        <? if($data['cat_name']=='deposits'){ ?>
        var capPeriod = $('.capitalization_period:checked').val();
        var isRepl = $('.is_replenishable:checked').val();
        var isCap = $('.is_capitalization:checked').val();
        <? } ?>
        <? if($data['cat_name']=='deposits' || $data['cat_name']=='credits'){ ?>
        var Period = $('#period').val();
        var sum = $('#sum').val();
        <? } ?>
        $.post('<?php echo $url; ?>', {"name":name,"bank":bank,"image":image,"descr":descr,
        
        <? if($data['cat_name']=='creditcards'){ ?>
	    "limit":limit,
	    "grace_period":gracePeriod,
        <? } ?>
        <? if($data['cat_name']=='creditcards' || $data['cat_name']=='credits'){ ?>
        "time_to_consider":timeToConsider,
        "approvement_documents":approvDocs,
        "approv_needed":approvNeeded,
        <? } ?>
        <? if($data['cat_name']=='deposits'){ ?>
        "capitalization_period":capPeriod,
        "is_replenishable":isRepl,
        "is_capitalization":isCap,
        <? } ?>
        <? if($data['cat_name']=='deposits' || $data['cat_name']=='credits'){ ?>
        "period":Period,
        "sum":sum,
        <? } ?>
        "min_rate":minRate,
        "max_rate":maxRate
	        
	        
        }, function(data){
            
            $('#myModal').modal( 'hide' );

            var btn_toolbar = $(".content[idoffer=<?php echo $template->get->id; ?>] .btn-toolbar").clone(true);
            $(".content[idoffer=<?php echo $template->get->id; ?>]").html( content );
            
            btn_toolbar.prependTo(".content[idoffer=<?php echo $template->get->id; ?>]");
            location.reload(); //перезагрузка страницы fix!!!
        });

        e.preventDefault();
        return false;
    });
</script>