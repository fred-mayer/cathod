<?php

class Tcatalog extends TModule
{
    protected $pagination = array();
    public function display( TTemplate $template )
    {
		$db = $template->db;
        $params = $this->getParams();
        $data['idmodule'] = $this->idmodule;
		//загрузка магазинов с бд
		$data['magazines']=$db->select("SELECT * FROM catalog_magazine")->toObject();
        if($params['id']){
	        //если id указано показывай товары
	        $data['id'] = $params['id'];
	        $data['items'] = $this->getItems($data['id']);
        }else{
	        //если не указано выводи информацию
        }
        $this->data = $data;
        if ( $template->route[0] == $this->getName() && $this->set_pos == 'section' ) // Главный модуль
        {
             //если есть категория тогда найди все подкатегории и проверь есть ли id товара   
             $route = $this->route($template->route); //возвращает массив, idItem - id товара, idCats - alias категории
             if(isset($route['idItem'])){ //страница товара
                 
             }elseif(isset($route['idCats'])){ //страница категории
                 $ii = count($route['idCats'])-1;
                 $this->data['items'] = $this->getCatItems($route['idCats'][$ii]);
                 $this->module_template = "category"; //записали шаблон отображения
             }else{
                 //главная страница магазина
                 // надо придумать что здесь отображать) может ничего?
             }
	}
	
        //$this->data = $template->db->select( 'SELECT id, content FROM content WHERE '.$where )->current();

        parent::display( $template );
    }
    public function getCatItems($alias){
        $db = $this->template->db;
        //Считываем категорию
        $cat = $db->select("SELECT * FROM catalog_cats WHERE `hide`=1 && `alias`='$alias'")->current();
        $this->data['cat'] = $cat;       
        
        //Создаем breadcrumbs
        $this->pagination = array();
        $this->getCatBreadcrumbs($cat->id);
        $this->breadcrumbs = array_reverse($this->breadcrumbs);
        $this->data['breadcrumbs'] = $this->breadcrumbs;
        
        //устанавливаем title *** не работает надо решить!!!!
        $this->template->setTitle($cat->name);
        
        //Считываем товары
        $sql = "SELECT i.*,m.name as mag_name,m.trekking_url,m.logo 
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.mag_id=m.id 
            WHERE i.catid=".$cat->id." AND i.hide=1 AND m.hide=1 ORDER BY i.sale, i.price ASC"; //*** добавить разделы страниц
        $items = $db->select($sql)->toObject();
        if(count($items)>0){
            for($i=0;$i<count($items);$i++){
                //добавляем рубли к ценам
                $currency = ($items[$i]->currencyid)? $items[$i]->currencyid:' <span class="currency">р</span>';
                $items[$i]->price .= $currency;
                $items[$i]->price_old .= $currency;

                //переводим размеры в массив
                $size = explode("]",str_replace("[", "", $items[$i]->size));
                $items[$i]->size = $size;
            }
        }else{
            //пробуем найти товары в подкатегориях
            $catsChild = $db->select("SELECT id FROM catalog_cats WHERE `hide`=1 && `parentid`='".$this->data['cat']->id."'")->toObject();
            $childIds = "";
            foreach($catsChild as $child){
                $childIds .= ",".$child['id'];
            }
            $childIds = substr($childIds,1);
            $items = $this->getItems($childIds,20);
        }
        return $items;
    }
    public function getCatBreadcrumbs($catid){
        $db = $this->template->db;
        $par = $db->select("SELECT * FROM catalog_cats WHERE id=".$catid)->current();
        $this->breadcrumbs[] = $par;
        if($par->parentid>0){
            $this->getCatBreadcrumbs($par->parentid);
        }
    }
    public function route($router){ // поиск категории и товаров в строке URI
        $i=1;
        $res = false;
        while(isset($router[$i]) && $router[$i]){
            if(is_numeric($router[$i])){ //если число
                $res['idItem'] = $router[$i];
            }else{
                $res['idCats'][] = $router[$i];
            }
            $i++;
        }
        return $res;
    }
    public function getItems($catids,$limit=0,$order="i.sale, i.price ASC"){
	    $db = $this->template->db;
	    //Считываем товары
            //*** добавить разделы страниц
            $sql = "SELECT i.*,m.name as mag_name,m.trekking_url,m.logo 
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.mag_id=m.id 
            WHERE i.catid IN (".$catids.") AND i.hide=1 AND m.hide=1 ORDER BY i.sale, i.price ASC";
            if($limit>0){
                $sql .= " LIMIT ".$limit;
            }
            $res = $db->select($sql)->toObject();
            if(count($res)>0){
                for($i=0;$i<count($res);$i++){

                    //добавляем рубли к ценам
                    $currency = ($res[$i]->currencyid)? $res[$i]->currencyid:' <span class="currency">р</span>';
                    $res[$i]->price .= $currency;
                    $res[$i]->price_old .= $currency;

                    //переводим размеры в массив
                    $size = explode("]",str_replace("[", "", $res[$i]->size));
                    $res[$i]->size = $size;
                }
            }else{ $res=false; }
	    return $res;
    }
    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'addMagazine', 'icon'=>'shopping-cart', 'text'=>'', 'title'=>'Добавить магазин для парсинга товаров');
        $buttons[] = array('action'=>'magazineCats', 'icon'=>'tasks', 'text'=>'', 'title'=>'Страницы для парсинга');
        $buttons[] = array('action'=>'parse', 'icon'=>'refresh', 'text'=>'', 'title'=>'Обновить товары с магазинов');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
?>