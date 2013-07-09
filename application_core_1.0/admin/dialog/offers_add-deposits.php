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
	    		<label class="control-label">Мин. сумма</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="sum" value="" /><span class="add-on">руб.</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Макс. срок:</label>
	    		<div class="controls">
	    			<div class="input-append">
	    			<input type="text" id="period" value="" /><span class="add-on">мес.</span>
	    			</div>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Капитализация:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="-1" /> На выбор вкладчика
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="0" /> Нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_capitalization" name="is_capitalization" value="1" /> Да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Пополнение:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="is_replenishable" name="is_replenishable" value="0" /> Нет
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="is_replenishable" name="is_replenishable" value="1" /> Да
	    			</label>
				</div>
	    	</div>
	    	<div class="control-group">
	    		<label class="control-label">Выплата %:</label>
	    		<div class="controls">
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="1" /> в конце срока
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="2" /> проценты вперёд
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="3" /> ежедневно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="4" /> еженедельно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="5" /> ежемесячно
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="6" /> ежеквартально
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="7" /> раз в полгода
	    			</label>
	    			<label class="radio">
	    				<input type="radio" class="capitalization_period" name="capitalization_period" value="8" /> ежегодно
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

        var bank = $('#banks').val();
        var urlForm = $('#urlForm').val();
        if($('#published').is(':checked')){
	        var published = 0;
        }else{ var published = 1; }
        
        var minRate = $('#min_rate').val(); //все
        var maxRate = $('#max_rate').val(); //все
        
        var capPeriod = $('.capitalization_period:checked').val();
        var isRepl = $('.is_replenishable:checked').val();
        var isCap = $('.is_capitalization:checked').val();

        var Period = $('#period').val();
        var sum = $('#sum').val();
        
        $.post('<?php echo $url; ?>', {"name":name,"bank":bank,
	        "urlForm":urlForm,"published":published,
        
        "capitalization_period":capPeriod,
        "is_replenishable":isRepl,
        "is_capitalization":isCap,
        
        "period":Period,
        "sum":sum,
        
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