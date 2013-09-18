<?php

class Tmenu extends TModule
{
    public $route;
    public $db;
    public function display( TTemplate $template )
    {
        $params = $this->getParams();
        $this->data = new module_menu($template->db,$params['name_group']);
        parent::display( $template );
    }

    public function getElementAdminToolbar($more,$attr=null, $buttons=null){
        $buttons[] = array('action'=>'editItem', 'icon'=>'pencil', 'text'=>'', 'title'=>'Редактировать статью','more'=>$more);
        $buttons[] = array('action'=>'delItem', 'icon'=>'remove', 'text'=>'', 'title'=>'Удалить статью','more'=>$more);
        return parent::getAdminToolbar( $attr, $buttons );
    }
    public function getAdminToolbar( $attr, $buttons=null )
    {
        $buttons[] = array('action'=>'add', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить пункт меню');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
class module_menu
{
    public $db;
    public $name_group;
    
    function __construct($db,$name_group) {
        $this->db = $db;
        $this->name_group = $name_group;
    }
    
    public function getMenuItems(){
        $sql = "SELECT m.*,p.alias FROM `menu` AS m LEFT JOIN `core_page` AS p ON m.id_page=p.id WHERE name_group='".$this->name_group."' AND id_parent=0 AND m.hide='show' ORDER BY m.order ASC";
        $items = array();
        $item = $this->db->select("$sql")->current();
        if ($item){
            return $this->db->select("$sql")->toObject();
        }else{
            return false;
        }   
    }
}
?>