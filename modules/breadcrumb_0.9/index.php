<?php

class Tbreadcrumb extends TModule
{
    public $route;
    public $db;
    public function display( TTemplate $template )
    {
        $route = $template->route;
        $this->db = $template->db;
        $link = "/";
        $this->data[] = array("title"=>"Главная", "link"=>$link);
        // route 0 ищем в pages
        if($route[0]){
            $page = $this->db->select("SELECT title FROM core_page WHERE `alias`='".$route[0]."'")->current('title');
            $this->data[] = array("title"=>$page, "link"=>$link.$route[0]);
        }
        
        parent::display( $template );
    }
    public function getAdminToolbar( $attr, $buttons=null )
    {
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
class module_breadcrumb_route extends TRoute
{
    
}
?>