<?php

class Tadmin_catalog extends TBAdmin
{
    public function delItem($get, $post){
        if($get['id']){
            $this->db->select("DELETE FROM catalog_items WHERE id=".$get['id'])->current();
        }
    }
    public function getItem($id){
        return $this->db->select('SELECT * FROM catalog_items WHERE id='.$id)->current();
    }
    public function editItem($get, $post){
        if ($post['category'] && $get['id']){
            $this->db->update('catalog_items',array('catid'=>$post['category']),'id='.$get['id']);
        }
    }
    public function edit( $get, $post )
    {
        if($post->category){
        	$category = $post->category."";
	        $this->db->update('core_modules',array('params'=>json_encode(array('id'=>$category))),'id='.$get->id);
        }
        //$this->db->update( 'content', array('content'=>$post->content), 'id='.$get->id->int() );
    }
    public function magazineCats($get, $post ){
        $cats = $this->db->select("SELECT * FROM catalog_cats")->toObject();
        foreach($cats as $cat){
            $mags = $this->db->select("SELECT * FROM catalog_magazine")->toObject();
            if(count($mags)>0):
            foreach($mags as $mag){
                unset($url);
                $url = $post['url_'.$cat->id.'_'.$mag->id];
                echo $cat->id."-".$mag->id."-".$url."/n";
                if(strlen($url)>0){
                    //проверяем есть ли?
                    $magCat = $this->db->select("SELECT * FROM catalog_mag_cats WHERE id_cat=". $cat->id. " AND id_mag=".$mag->id)->current();
                    if($magCat->url){ //update
                        $this->db->update("catalog_mag_cats",array('url'=>$url),"id_cat=". $cat->id. " AND id_mag=".$mag->id);
                    }else{ //insert
                        $this->db->insert("catalog_mag_cats",array('url'=>$url,"id_cat"=>$cat->id,"id_mag"=>$mag->id));
                    }
                }else{ // нет url возможно стерли - значит ненадо!
                    $magCat = $this->db->select("SELECT * FROM catalog_mag_cats WHERE id_cat=". $cat->id. " AND id_mag=".$mag->id)->current();
                    if($magCat->url){ //update
                        $this->db->select("DELETE FROM catalog_mag_cats WHERE id_cat=". $cat->id. " AND id_mag=".$mag->id);
                    }
                }
            }
            endif;
        }
    }
    public function getMagCats($tree){
        $mags = $this->db->select("SELECT * FROM catalog_magazine")->toObject();
        $i=0;
        $res = array();
        foreach($tree as $cat){
            $j=0;
            $res[$i]['title']=$cat['title'];
            $res[$i]['catid']=$cat['value'];
            foreach($mags as $mag){
              
                $res[$i]['mag'][$j]['name'] = $mag->name;
                $res[$i]['mag'][$j]['id'] = $mag->id;
                $magCat = $this->db->select("SELECT * FROM catalog_mag_cats WHERE id_cat=". $cat['value']. " AND id_mag=".$mag->id)->current();
                if(isset($magCat->url)){
                    $res[$i]['mag'][$j]['url'] = $magCat->url;
                }
                $j++;
            }
            $i++;
        }
        return $res;
    }
    public function getTree($separator = 'array', $par=0,$tree='',&$j=0){ //сбор дерева категорий
    	
        $res = $this->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$par." ORDER BY `order` DESC")->toObject();
        $i=0;
        //print_r($res);
        foreach($res as $cat){
        	$ex = $this->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$cat->id)->toObject();
        	if($separator=='array'){
	        	if(count($ex)>0){ //значит есть подкатегории
		        	$tree[$i] = $this->getTree('array',$cat->id,$tree[$i]);
	        	}
	        	$tree[$i]['name'] = $cat->name;
	        	$i++;
        	}else{
        		$tree[$j]['title'] = $separator.$cat->name;
        		$tree[$j]['value'] = $cat->id;
        		$j++;
        		if(count($ex)>0){ //значит есть подкатегории
		        	$tree = $this->getTree($separator.$separator,$cat->id,$tree,$j);
	        	}
        	}
        }
        return $tree;
    }
	
    public function insert( $post )
    {
        if($post->isxml=='1' && $post->xml){ // парсим базу xml
        	$this->parseXML($post);
        }
        return true;
    }
	public function addMagazine($get, $post){
		$this->db->insert('catalog_magazine',array('name'=>$post->name,'url'=>$post->url,'trekking_url'=>$post->trekking_url,'logo'=>$post->logo,'script_parser'=>$post->script_parser));
	}
	public function parseHTML(){ //парсинг html магазинов по скриптовым файлам
		set_time_limit( 600 );
		$db = $this->db;
		$mags = $db->select("SELECT * FROM catalog_magazine")->toObject();
		include_once( CLASS_DIR.'phpQuery.php' );
		foreach($mags as $mag){
			include_once(MODULES_DIR."catalog/parser_script/".$mag->script_parser);
		}
		echo "Парсинг закончен!";
	}
	public function parseXML($post){ //парсинг прикрепленного xml 
		ini_set("max_execution_time", "600");
	        $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
	        $dom->load("/home/user/data/www/dev1.cathod.ru/media/".$post->xml); // Загружаем XML-документ из файла в объект DOM
	        if($post->truncate=='1'){
		        $this->db->select('TRUNCATE TABLE catalog_cats');
		        $this->db->select('TRUNCATE TABLE catalog_items');
	        }
	        //парсим категории
	        $cats = $dom->getElementsByTagName('category');
	        for($i=0; $i<$cats->length; $i++){
		       $cat = $cats->item($i);
		       $id = $cat->getAttribute("id");
		       $parentid = $cat->getAttribute("parentid");
		       $title = $cat->nodeValue;
		       //запись категории
		       $id = $this->db->insert( 'catalog_cats', array('id'=>$id, 'parentid'=>$parentid, 'name'=>$title) );
	        }
	        //парсим товары
	        $offers = $dom->getElementsByTagName('offer');
	        for($i=0; $i<$offers->length; $i++){
	        	$offer = $offers->item($i);
	        	
	        	$group = $offer->getAttribute("group_id");
	        	$url = $offer->getElementsByTagName('url')->item(0)->nodeValue;
	        	$price = $offer->getElementsByTagName('price')->item(0)->nodeValue;
	        	$currency = $offer->getElementsByTagName('currencyId')->item(0)->nodeValue;
	        	$category = $offer->getElementsByTagName('categoryId')->item(0)->nodeValue;
	        	$picture = $offer->getElementsByTagName('picture')->item(0)->nodeValue;
	        	$name = $offer->getElementsByTagName('name')->item(0)->nodeValue;
	        	$vendor = $offer->getElementsByTagName('vendor')->item(0)->nodeValue;
	        	$description = $offer->getElementsByTagName('description')->item(0)->nodeValue;
	        	//параметры
	        	$params = $offer->getElementsByTagName('param');
	        	for($j=0;$j<$params->length; $j++){
		        	$param = $params->item($j);
		        	
		        	$exp_params[$j]['name'] = $param->getAttribute("name");
		        	$exp_params[$j]['value'] = $param->nodeValue;
	        	}
	        	$id = $this->db->insert( 'catalog_items', array('group_id'=>$group, 'url'=>$url, 'price'=>$price,'currencyid'=>$currency,'catid'=>$category,'picture'=>$picture,'name'=>$name,'vendor'=>$vendor,'description'=>$description,'params'=>json_encode($exp_params)) );
	        }
	        echo json_last_error();
	        
	        
	}
}

?>