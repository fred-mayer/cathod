<? 
    $data = $this->getData();
    if(count($data) && $data['result']):
    $offer = $data['result'];
    //print_r($offer);
?>
<div class="block_offer_detail">
	<h2><? echo $data['title'] ?></h2>
	<img class="img-polaroid" style="float:left; margin-right:10px;" src="<? echo $offer->images ?>"/>
	<p class="bank_name"><? echo $data['result']->bank_name ?></p>
	<p class="descr">
		<? echo $data['result']->descr ?>
	</p>
	<div class="clearfix"></div>
	<p class="lead">
			<table>
				<tr>
					<th>мин. ставка</th><th>макс. сумма</th><th>макс. срок</th><th>подтверждение дохода</th><th>рассмотрение заявки</th>
				</tr>
				<tr>
					<td><? echo $offer->min_rate ?></td><td><? echo $offer->max_sum ?></td><td><? echo $offer->max_period ?></td><td><? echo $offer->proof_income ?></td><td><? echo $offer->cons_aplic ?></td>
				</tr>
			</table>
	</p>
			<a href="<? echo $offer->linkForm ?>" class="btn btn-large btn-success">Оформить</a>
</div>
<?
 endif;
?>