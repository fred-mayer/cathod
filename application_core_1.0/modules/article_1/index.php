<?php

class Tarticle extends TModule
{
    public $route;
    public function display( TTemplate $template )
    {
        $this->route = new module_article_route($template->get,$this);
        $params = $this->getParams();
        if(isset($params['idcat']) && !$this->route->subURIModule){ //показываем категории
            $items = new module_article_cats($params, $template,$this);
            $this->data = $items;
            if(!isset($params['template']))  $this->module_template = "blog";
        }elseif (isset($params['id']) || $this->route->subURIModule===true) { //показываем одну статью
            echo "Страница статьи";
            $item = new module_article_item($params, $template,$this);
            if($this->route->subURIModule===true){
                $item->getItemByAlias($this->route[1]);
            }else{
                $item->getItemById($params['id']);
            }
            $this->data = $item;
        }
        parent::display( $template );
    }

    public function getElementAdminToolbar($more,$attr=null, $buttons=null){
        $buttons[] = array('action'=>'editItem', 'icon'=>'pencil', 'text'=>'', 'title'=>'Редактировать статью','more'=>$more);
        $buttons[] = array('action'=>'delItem', 'icon'=>'remove', 'text'=>'', 'title'=>'Удалить статью','more'=>$more);
        return parent::getAdminToolbar( $attr, $buttons );
    }
    public function getAdminToolbar( $attr, $buttons=null )
    {
        $buttons[] = array('action'=>'settings', 'icon'=>'cog', 'text'=>'', 'title'=>'Параметры');
        $buttons[] = array('action'=>'add', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить статью');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
class module_article_item
{
    public $db;
    public $template;
    public $module;
    public $route;
    public $item;
    
    public function __construct(array $params, TTemplate $template, Tarticle $module) {
        $this->db = $template->db;
        $this->template = $template;
        $this->module = $module;
        $this->route = $this->module->route;
    }
    
    public function getItemByAlias($alias){
        if(!($this->item = $this->db->select("SELECT * FROM article_items WHERE alias='$alias' AND hide='show'")->current())){
            $this->template->_404();
        }
    }
    public function getItemById($id){
        if(!($this->item = $this->db->select("SELECT * FROM article_items WHERE id=$id AND hide='show'")->current())){
            $this->template->_404();
        }
    }
}
class module_article_cats extends module_article_item
{
    
    public $catid;
    public $cols=3;
    
    
    public function __construct(array $params, TTemplate $template, Tarticle $module) {
        
        $this->catid = $params['idcat'];
        if(isset($params['cols']))
            $this->cols = $params['cols'];
        parent::__construct($params, $template, $module);
    }
    
    public function getItems(){
        $sql = "SELECT * FROM article_items WHERE id_cat=".$this->catid. " AND hide='show' ORDER BY `date` ASC";
        if($this->db->select($sql)->current()){
            return $this->db->select($sql)->toObject(); 
        }else{
            return false;
        }
    }
    
    static public function getRouteLink(Tarticle $module){
        
    }
}
class module_article_route extends TRoute
{
    protected $module;
    public $isMain=false;
    public $subURIModule = false; //true если внутренняя страница главного модуля!
    public $routeMain="article";
    function __construct(TMethod $get, Tarticle $module){
        $this->module = $module;
        parent::__construct($get);
        $params = $module->getParams();
        if(isset($params['mainlink']))
            $this->routeMain = $params['mainlink'];
        if(($this[0] && $this->module->set_pos=="section") || ($this[0]==$this->module->getName() && $this->module->set_pos=="section")){ //модуль главный
            $this->isMain=true;
        }
        if($this[1] && $this->isMain){
            $this->subURIModule=true;
        }
    }
    
    public function getRouteArticle($alias){
        if($this[0] && $this->module->set_pos=="section" && $this[0]!=$this->module->getName()){ //значит страница - неглавная стр., главн модуль и несовп uri
            $this->routeMain = $this[0];      
        }
        return DS.$this->routeMain.DS.$alias.DS;
    }
}
?>