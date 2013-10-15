<?php

/**
 * @author Fred Mayer <mail@site-don.ru>
 * @copyright (c) 2013, Fred Mayer
 * 
 */
class module_catalog_categories
{
    public $get;
    public $db;
    public $module; // Tcatalog
    public $route; // module_catalog_route
    
    public $limit = 60;
    public $start =0;
    public $end = 0;
    public $pag_count = 0; //количество товаров
    public $items=array();
    public $breadcrumbs;
    
    public $main_link = ""; //главная страница каталога - по умолчанию - главная сайта
    public $main_title = "Главная";
    
    protected $cats=array();
    
    function __construct(TMethod $get, Tcatalog $module) {
        $this->module = $module;
        $this->db = $module->db;
        $this->route = $module->route;
        $this->get = $get;
    }
    public function getCatItems($id=0)
    {
        //вычисляем диапазон выборки постранично
        $this->end = $this->limit;
        $this->route->getPagination($this->start,$this->end,$this->limit);

        //считываем категорию
        $cur_cat = $this->route->getCurCat();
        $cats = $this->getCatsRecruse("",$cur_cat);
        $category = $cats[0];
        
        //breadcrumbs
        $this->breadcrumbs = $this->getBreadcrumps($cats);
        
        //устанавливаем title
        $this->module->template->setTitle($category->name. " - ". $this->module->template->title);
        
        //формирование запроса
        $sql_fields = "i.*,m.name as mag_name,m.trekking_url,m.logo,a.field_value as sizes";
        $sql_pag_fields = "COUNT(i.id)";
        
        $sql = "SELECT {fields} 
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.id_mag=m.id LEFT JOIN catalog_attr AS a ON i.id=a.iditem
            WHERE";
        
        if($id===0){ // если не передан id значит берем по uri
            $sql .= " i.id_cat=".$category->id;
        }elseif(is_array($id)){ // если массив - значит несколько категорий
            $catids = implode(",",$id);
            $sql .= " i.id_cat IN (".$catids.")";
        }else{ // просто id категории
            $sql .= " i.id_cat=".$id;
        }
        $sql .= " AND a.field_name='size' AND i.hide='false' AND m.hide=1";
        // id_mag
        if(isset($this->get->mag)){
            $sql .=" AND m.id=".$this->module->template->get->mag;
        }
        //установка fields
        $sql_pag = str_replace("{fields}", $sql_pag_fields, $sql);
        $sql = str_replace("{fields}", $sql_fields, $sql);
        $sql .= " ORDER BY i.sale DESC"; //*** добавить возможность сортировки
        $sql .= " LIMIT ".$this->start.",".$this->end; //*** разделы страниц
        
        //считываем количество
        $pag = $this->db->select($sql_pag)->current();
        if((int)$pag[$sql_pag_fields]!=0)
        {
            $this->pag_count = (int) $pag[$sql_pag_fields];
            $items = $this->db->select($sql)->toObject();
            foreach ($items as $item){
                $this->formateItem($item);
            }
            return $this->items = $items;
        }else{
            //если мы смотрим через uri значит ищем рекрусивно подкатегории
            if($id===0){
                $catsChild = $this->db->select("SELECT id FROM catalog_cats WHERE `hide`=1  && `parentid`='".$category->id."'")->toObject();
                $childIds = array();
                foreach($catsChild as $child){
                    $childIds[]= $child['id'];
                }
                return $this->getCatItems($childIds);
            }else{
                echo $this->module->template->displaySystemMes('К сожалению в этой категории нет товаров.');
                return false;
            }
        }
    }
    public function formateItem(& $item)
    {
        //добавляем рубли к ценам
        $currency = ($item->currencyid)? $item->currencyid:' <span class="currency">р</span>';
        $item->price .= $currency;
        $item->price_old .= $currency;
        
        //переводим размеры в массив
        if(!empty($item->sizes)){
            $size = json_decode($item->sizes);
            $item->size = $size;
            $item->size_str = (is_array($size))? implode(",",$size):$size;
        }
        
        //ссылка для страницы товара
        $item->item_url = DS.$this->route->routeMain.DS.implode($this->route->route['cat'],"/").DS.$item->id;
        
    }
    /*
     * возврващает список дерефо категорий
     */
    public function getCatsRecruse($id_cat="",$alias="")
    {
        $sql = "SELECT * FROM catalog_cats WHERE ";
        if(empty($alias))
            $sql .="id=".$id_cat;
        else
            $sql .="alias='".$alias."'";
        $cat = $this->db->select($sql)->current();
        
        $this->cats[] = $cat;
        if($cat->parentid!="0")
        {
            return $this->getCatsRecruse($cat->parentid);
        }else{
            $cats = $this->cats;
            $this->cats = array();
            return $cats;
            
        }
    }
    public function getBreadcrumps($cats,$item=null)
    {
        $cats = array_reverse($cats);
        $br_link = $this->main_link.DS;
        $br_title = $this->main_title;
        $br='<ul class="breadcrumbs unstyled">
                <li><a href="'.$br_link.'">'.$br_title.'</a></li><li>></li>';
        $i = 2;
        foreach($cats as $cat)
        {
            $br_link .= $cat->alias.DS;
            $br_title = $cat->name;
            if($i==count($cats)){
                $br.= '<li><a href="'.$br_link.'">'.$br_title.'</a></li><li>></li>';
                $i=0;
            }else{
               $br.= '<li><b>'.$br_title.'</b></li>'; 
            }
            $i++;
        }
        if(!empty($item)){
            $br_title = $item->name;
            $br.= '<li>'.$br_title.'</li><li>></li>';
        }
        $br.='</ul>';
        return $br;
    }
    public function getPagination(){
        $count_p = floor($this->pag_count/$this->limit); //количество страниц
        $url = $_SERVER["REQUEST_URI"];
        $purl = parse_url($url);
        $pathURL = $purl['path']."?".$this->route->getParamsToURI();
        $p = (isset($this->get->p))? $this->get->p->int():1;
        
        $pag = '<div class="clearfix"></div>';
        if($count_p>1){
            $pag .= '<div class="pagination">';
            $pag .= '<ul>
                     <li class="'.(($p==1)? "disabled":"") .'"><a href="<'.$pathURL."&p=".($p-1).'">Назад</a></li>';
            for($i=1;$i<=$count_p;$i++){
                $pag.= '<li class="'.(($p==$i)? "active":"").'"><a href="'.$pathURL."&p=".$i.'">'.$i.'</a></li>';
            }
            $pag .= '<li class="'.(($p==$count_p)? "disabled":"").'"><a href="'.$pathURL."&p=".($p+1).'">Вперед</a></li>
                    </ul>
                    </div>';
        }
        return $pag;
    }
}
class module_catalog_route extends TRoute
{
    public $get;
    public $db;
    public $module;
    public $cats=array();
    public $routeMain="catalog";
    public $parameters = array(); //Параметры фильтрации по магазину, бренду и т.п.
    
    public $route;
    
    function __construct(TMethod $get, Tcatalog $module) {
        $this->get = $get;
        $this->module = $module;
        $this->db = $module->db;
        
        parent::__construct($get);
        
        $this->route = $this->buildRoute($this);
        if(isset($this->get->mag)){ // фильтр по магазину
            $this->route['id_mag'] = $this->get->mag;
            $this->parameters['mag'] = $this->get->mag;
        }
    }
    
    /**
     * 
     * @name buildRoute
     * Поиск категории и товаров в строке URI
     * @param type $router
     * @return type
     */
    public function buildRoute($router){ // 
        $i=1;
        $res = false;
        while(isset($router[$i]) && $router[$i]){
            if(is_numeric($router[$i])){ //если число
                $res['item'] = $router[$i];
            }elseif(!empty($router[$i])){
                $res['cat'][] = $router[$i];
            }
            $i++;
        }
        return $res;
    }
    public function getCurCat()
    { //возвращает текущую категорию будь то страница товара или страница категории
        return end($this->route['cat']);
    }
    public function getPagination(& $start,& $end,$limit)
    { //вычисляет страницу
        if (isset($this->get->p) && $this->get->p!="1"){
            $p = $this->get->p->int();
            $start = ($p * $limit)+1;
            $end = ($p * $limit) + $limit;
        }else{
            $start = 0;
        }
    }
    public function getLinkItem($id)
    {
        //ищем родительскую категорию
        $id_cat = $this->db->select("SELECT id_cat FROM catalog_items WHERE id=".$id)->current("id_cat");
        //делаем все категории рекрусивно
        $cats = $this->getCatsRecruse($id_cat);
        $catsR = array_reverse($cats);
        if(count($catsR))
        {
            $link = DS.$this->routeMain.DS;
            foreach ($catsR as $c){
                $link .= $c->alias.DS;
            }
            $link.=$id;
            return $link;
        }else{
            return false;
        }
        
    }
    public function getParamsToURI($exclude=null)
    {
        $str = "";
        foreach($this->parameters as $key=>$value)
        {
            if($key != $exclude){
                $str.="&".$key."=".$value;
            }
        }
        return $str;
    }
    
    
}
?>
