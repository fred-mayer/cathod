<?php
	//Поиск страниц категорий для парсинга
	$cats = $db->select("SELECT * FROM catalog_mag_cats WHERE id_mag=".$mag->id)->toObject();
        if(count($cats)){
           $db->select("DELETE FROM catalog_items WHERE mag_id=".$mag->id); //удаляем все товары конкретного магазина
	foreach($cats as $cat){//парсим страницу одной категории
		$main = file_get_contents($cat->url);
		$doc = phpQuery::newDocumentHTML($main, $charset = 'utf-8');
		
		//$jproducts = $doc->find("div.divProductsArea > div.divProducts"); //блок продуктов
		$jproduct = $doc->find("ul.catalog li"); //блок продуктов
		foreach($jproduct as $item){ //проход по продуктам
			$pq = pq($item);
			$it['picture'] = "http://www.trendsbrands.ru".$pq->find("div.img img")->attr("src");
			$it['name'] = trim($pq->find("div.img img")->attr("alt"));
			$it['price'] = eregi_replace("([^0-9])", "", $pq->find("div.price strong")->text());
			$it['price_old'] = eregi_replace("([^0-9])", "", $pq->find("div.price .old-price")->text());;
			$it['sale'] = str_replace("%","",trim($pq->find(".secret_percent")->text()));
			//должны зайти на страницу товара и взять размеры!
			$it['url'] = "http://www.trendsbrands.ru".$pq->find('div.img a')->attr("href");
			$itemPage = file_get_contents($it['url']);
                        $it['url'] = "http://ucl.mixmarket.biz/uni/clk.php?id=1294954555&zid=1294952941&prid=1294931875&stat_id=0&sub_id=&redir=".$it['url'];
			$itPage = phpQuery::newDocumentHTML($itemPage);
			$sizeOption = $itPage->find("#content_top #size option");
                        $it['size'] ="";
                        foreach($sizeOption as $opt){
                            $pqOpt = pq($opt);
                            $size = trim($pqOpt->text());
                            $size = explode("(Российский размер",$size);
                            
                            $it['size'].= ($size[0]!='Выбрать размер')? "[".trim($size[0])."]":"";
                        }
			
			// заносим товар в БД
			$it['mag_id'] = $mag->id;
			$it['catid'] = $cat->id_cat;
			$idItem = $db->insert('catalog_items',$it); //записали в основную БД
			if($idItem){
                            echo "Записан товар - ".$it['name']." -с id ".$idItem."<br/>";
                        }
		}
	}
        }
	
?>