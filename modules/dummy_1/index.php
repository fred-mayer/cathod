<?php

class Tdummy extends TModule
{
    public $route;
    public $db;
    public function display( TTemplate $template )
    {
        $route = $template->route;
        $this->db = $template->db;
                
        parent::display( $template );
    }
    public function getAdminToolbar( $attr, $buttons=null )
    {
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
class module_news_phpshop_route extends TRoute
{
    
}
?>