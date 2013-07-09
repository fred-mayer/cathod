<?php
	//Поиск страниц категорий для парсинга
	$cats = $db->select("SELECT * FROM catalog_mag_cats WHERE id_mag=".$mag->id)->toObject();
        if(count($cats)){
           $db->select("DELETE FROM catalog_items WHERE mag_id=".$mag->id); //удаляем все товары конкретного магазина
	foreach($cats as $cat){//парсим страницу одной категории
		$main = file_get_contents($cat->url);
		$doc = phpQuery::newDocumentHTML($main, $charset = 'utf-8');
		
		//$jproducts = $doc->find("div.divProductsArea > div.divProducts"); //блок продуктов
		$jproduct = $doc->find("div.divProducts a"); //блок продуктов
		foreach($jproduct as $item){ //проход по продуктам
			$pq = pq($item);
                        $itStyle = mb_convert_encoding($pq->find("div.divProduct .divTagging")->attr("style"),"ISO-8859-1","utf-8");
			$itStyle = str_replace("');","",$itStyle);
			$it['picture'] = str_replace("background-image: url('","",$itStyle);
			$it['name'] = mb_convert_encoding(trim($pq->find(".divProduct h1")->html()),"ISO-8859-1","utf-8");
			$it['price'] = eregi_replace("([^0-9])", "", $pq->find(".divProduct .divPrice .spanUnit")->text());
			$it['price_old'] = eregi_replace("([^0-9])", "", $pq->find(".divProduct .divPrice .spanOldPrice")->text());;
			$it['sale'] = str_replace("%","",$pq->find(".divProduct .divDiscount .spanValue")->text());
			//должны зайти на страницу товара и взять размеры!
			$it['url'] = substr($mag->trekking_url.$pq->attr("href"),0,-1);
			$itemPage = file_get_contents($mag->url.$pq->attr("href"));
			$itPage = phpQuery::newDocumentHTML($itemPage);
			$sizeOption = $itPage->find("#cmbSizes option");
                        $it['size'] ="";
                        foreach($sizeOption as $opt){
                            $pqOpt = pq($opt);
                            $size = $pqOpt->val();
                            $it['size'].= ($size!="-1")? "[".$size."]":"";
                        }
			
			// заносим товар в БД
			$it['mag_id'] = $mag->id;
			$it['catid'] = $cat->id_cat;
                        $it['url'] .="x"; //x - не хватает вконце ссылки иначе не работает переход!
			$idItem = $db->insert('catalog_items',$it); //записали в основную БД
			if($idItem){
                            echo "Записан товар - ".$it['name']." -с id ".$idItem."<br/>";
                        }
		}
	}
        }
	
?>