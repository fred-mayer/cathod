<? 
    $data = $this->getData();
    if(count($data) && $data['result']):
    $offers = $data['result'];
?>
<div class="block_offers" offercat="<? echo $data['category'] ?>">
	<h2 ><? echo $data['title'] ?></h2>
	
	<!-- Блок фильтра -->
	<? if(isset($data['isFilter'])): ?>
		<form action="" method="get" class="form-inline filter-form" name='offers_filter'>
			<div class="input-append inline rate-r">
				<input class="span2 input-small" placeholder="Ставка от" name="rate" id="rate" type="text" value="<? echo (isset($template->get->rate))? $template->get->rate:null ?>" />
				<span class="add-on">%</span>
				<div class="range range-rate inline"></div>
			</div>
			<div class="input-append inline">
				<input class="span2 input-small" placeholder="Сумма" type="text" name="sum" id="sum" value="<? echo (isset($template->get->sum))? $template->get->sum:null ?>"/>
				<span class="add-on"> руб.</span>
					<div class="range range-sum inline"></div>
			</div>
			<div class="input-append inline">
				<input class="span2 input-small" placeholder="Срок" type="text" name="period" id="period" value="<? echo (isset($template->get->period))? $template->get->period:null ?>"/>
				<span class="add-on"> мес.</span>
					<div class="range range-period inline"></div>
			</div>
			<input class="btn" type="submit" name="submit" value="ок" />
			<? if(isset($data['isFilterSubmit'])){ ?><input class="btn" type="reset" name="reset" value="сбросить" /><? } ?>
		</form>
		 <script type="text/javascript">
			$(document).ready(function() {
				$( ".range-rate" ).slider({
					range: false,
					min: 5,
					max: 20,
					step: 0.1,
					values: [ 10 ],
					slide: function( event, ui ) {
						$( "#rate" ).val( ui.values[ 0 ]);
					}
				});
				$( ".range-sum" ).slider({
					range: false,
					min: 10000,
					max: 2000000,
					step: 10000,
					values: [ 100000 ],
					slide: function( event, ui ) {
						$( "#sum" ).val( ui.values[ 0 ]);
					}
				});
				$( ".range-period" ).slider({
					range: false,
					min: 6,
					max: 180,
					step: 1,
					values: [ 24 ],
					slide: function( event, ui ) {
						$( "#period" ).val( ui.values[ 0 ]);
					}
				});
				//$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );
			});
		</script>
	<? endif; ?>
	<div class="row-fluid">
		<? 
		$cc = count($offers);
		if($cc==1){$spanN = 12;}else{
			$spanN = 6;
		}
		foreach($offers as $offer){ ?>
		<div class="span<? echo ($offer['special']==1)? 12:$spanN ?>"><div class="offer <? if($offer['published']==0){ ?>unpublished<? } ?>" idoffer="<? echo $offer['id_offer'] ?>">

					<img class="img-polaroid" style="float:left; margin-right:10px;" src="<? echo $offer['images'] ?>"/>
					<h5 class="name"><a href="<? echo /*$offer['linkOffer']*/ $offer['linkForm'] ?>"><? echo $offer['name'] ?></a></h5>
					<? if($offer['bank_name']): ?>
					<p class="bank_name"><? echo $offer['bank_name'] ?></p>
					<? endif; ?>
					
					<? if(isset($data['isFormBut'])){ ?>
					<a href="<? echo $offer['linkForm'] ?>" class="btn btn-warning pull-right">Оформить</a>
					<? } ?>
			<div class="clearfix"></div>
			<table>
				<tr>
					<? if($offer['max_rate']==0){ ?>
					<th>мин. ставка</th>
					<? } ?>
					<? if($offer['max_rate']>0 && $offer['min_rate']!=$offer['max_rate'] ){ ?>
						<th>макс. ставка</th>
					<? } ?>
					<th>Мин. сумма</th><th>Макс. срок</th><th>Капитализация</th><th>Пополнение</th>
					<? if(isset($offer['capitalization_period'])){ ?>
					<th>Выплата %</th>
					<? } ?>
				</tr>
				<tr>
					<? if($offer['max_rate']==0){ ?>
					<td><? echo $offer['min_rate'] ?></td>
					<? } ?>
					<? if($offer['max_rate']>0 && $offer['min_rate']!=$offer['max_rate'] ){ ?>
						<td><? echo $offer['max_rate'] ?></td>
					<? } ?>
					<td><? echo $offer['sum'] ?></td><td><? echo $offer['period'] ?></td><td><? echo $offer['is_capitalization'] ?></td><td><? echo $offer['is_replenishable'] ?></td>
					<? if(isset($offer['capitalization_period'])){ ?>
					<td><? echo $offer['capitalization_period'] ?></td>
					<? } ?>
				</tr>
			</table>
			<p class="descr"><? echo $offer['descr'] ?></p>
			
		</div></div>
		<? } ?>
	</div>
</div>
<?
 endif;
?>