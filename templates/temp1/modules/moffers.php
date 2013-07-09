<? 
    $data = $this->getData();
?>
<div class="block_offers">
	<h2><? echo $data[0]->cat_name ?></h2>
	<? foreach($data as $offer){ ?>
	<div class="offer">
		<h3 class="name"><? echo $offer->name ?></h3>
		<? if($offer->bank_name): ?>
		<div class="bank_name"><? echo $offer->bank_name ?></div>
		<? endif; ?>
		<table>
			<tr>
				<th>валюта</th><th>мин. ставка</th><th>макс. сумма</th><th>макс. срок</th><th>комиссии</th><th>особенности</th>
			</tr>
			<tr>
				<td><? echo $offer->currency ?></td><td><? echo $offer->min_rate ?></td><td><? echo $offer->max_sum ?></td><td><? echo $offer->max_period ?></td><td><? echo $offer->comission ?></td><td><? echo $offer->features ?></td>
			</tr>
		</table>
	</div>
	<? } ?>
</div>