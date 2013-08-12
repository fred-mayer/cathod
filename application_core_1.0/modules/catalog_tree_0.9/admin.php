<?php

class Tadmin_catalog_tree extends TBAdmin
{
    public function order($get, $post){
        if($post->cats){
            $cats = explode(",",$post->cats);
            for($i=0;$i<count($cats);$i++){
                $this->db->update('catalog_cats',array('order'=>$i),'id='.$cats[$i]);
            }
        }
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
    public function delCat($get, $post){
        if($post->idcats){
            //проверяем есть ли подкатегории этой категории
            $childs = $this->db->select("SELECT id FROM catalog_cats WHERE parentid=".$post->idcats)->current();
            $ids ='';
            define('ZAP',",");
            if($childs){
                $ids .= ZAP.$childs->id;
                while($childs->next() ){
                    $ids .= ZAP.$childs->id;
                }
            }
            $ids .= ZAP.$post->idcats;
            $id = $post->idcats."";//костыль(
            $this->db->select("DELETE FROM catalog_cats WHERE id=".$id)->current();
            $this->db->select("DELETE FROM catalog_mag_cats WHERE id_cat=".$id)->current();
        }
    }
    public function addCat( $get, $post )
    {
        
        if($post->alias ==""){
            $alias = new TString($post->name);
            $alias = $alias->toTranslit();
        }else{
            $alias = $post->alias;
        }
        //определяем последний order
        $order = $this->db->select("SELECT `order` FROM catalog_cats WHERE parentid=".$post->parent. " ORDER BY `order` DESC LIMIT 1")->current();
        $order = $order->order+1;
        $alias = str_replace(" ", "-", $alias);
        $this->db->insert( 'catalog_cats', array('name'=>$post->name,'alias'=>$alias,'parentid'=>$post->parent,'order'=>$order));
    }
    
    public function edit( $get, $post )
    {
        $this->db->update( 'content', array('content'=>$post->content), 'id='.$get->id->int() );
    }
    
    public function insert( $post )
    {
        if($post->isxml=='1' && $post->xml){ // парсим базу xml
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
		        	
		        	$exp_params[$param->getAttribute("name")] = $param->nodeValue;
	        	}
	        	$id = $this->db->insert( 'catalog_items', array('group_id'=>$group, 'url'=>$url, 'price'=>$price,'currencyid'=>$currency,'catid'=>$category,'picture'=>$picture,'name'=>$name,'vendor'=>$vendor,'description'=>$description,'params'=>json_encode($exp_params)) );
	        }
	        
        }
        return array();
    }
}

?>