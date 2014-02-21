<?php

class Tcatalog_mags extends TModule
{
    public function display( TTemplate $template )
    {
        $params = $this->getParams();
        include_once MODULES_DIR.'catalog_0.9/helper.php';
        $route = new module_catalog_route($template->get, new Tcatalog($template,"catalog","0.9"));
        $url = $_SERVER["REQUEST_URI"];
        $purl = parse_url($url);
        
        if ( !isset($route->route['item']) && isset($route->route['cat'])) // если не страница товара и не главная
        {
            $mags = $template->db->select("SELECT * FROM catalog_magazine WHERE hide=1")->toObject();
            foreach ($mags as $mag){
                $mag->link = $purl['path']."?".$route->getParamsToURI("mag")."&mag=".$mag->id;
            }
            $alllink = $purl['path']."?".$route->getParamsToURI("mag");
            $this->data['mags'] = $mags;
            $this->data['alllink'] = $alllink;
            parent::display( $template );
        }
    }
    
    public function getAdminToolbar( $attr, $buttons=null )
    {
        //$buttons[] = array('action'=>'addCat', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить категорию');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>