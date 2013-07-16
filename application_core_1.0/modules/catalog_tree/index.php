<?php

class Tcatalog_tree extends TModule
{
    public $i = 0;
    public function display( TTemplate $template )
    {
        $params = $this->getParams();
        
        if ( $template->route[1]) // если есть категория запарси route
        {
	   $route = $this->route($template->route);
	}
        $this->data = $this->getTreeHTML();
        if($this->data !== false)
            parent::display( $template );
    }
    public function getTreeHTML($par=0,$link='/catalog/',$tree=''){ //сбор дерева категорий
    	
        $defaultLink = $link;
        $res = $this->template->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$par.' ORDER BY `order` ASC')->toObject();
        if (count($res)>0){
            $i=0;
            //print_r($res);
            $tree .= '<ul class="nav nav-list">';
            $adminActions = ( $this->template->auth->isAuthorized )? '<i class="action delete icon-trash"></i>':"";
            foreach($res as $cat){
                    $link .= $cat->alias.'/';
                    $tree .= ($par==0)? '<li class="nav-header" idcat="'.$cat->id.'">'.$cat->name .$adminActions.'</li>':'<li class="" idcat="'.$cat->id.'"><a href="'.$link.'">'.$cat->name .'</a>'.$adminActions.'</li>';
                    $ex = $this->template->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$cat->id)->toObject();
                    if(count($ex)>0){ //значит есть подкатегории
                            $tree .= $this->getTreeHTML($cat->id,$link);
                    }
                    $link = $defaultLink;
                    $i++;
            }
            $tree .= '</ul>';
            return $tree;
        }else{
            echo $this->template->displaySystemMes('Нет категорий');
            return false;
        }
    }
    public function getTree($par=0,$tree=''){ //сбор дерева категорий
    	
        $res = $this->template->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$par)->toObject();
        $i=0;
        //print_r($res);
        foreach($res as $cat){
        	$tree[$i]['name'] = $cat->name;
        	$ex = $this->template->db->select( 'SELECT * FROM catalog_cats WHERE parentid='.$cat->id)->toObject();
        	if(count($ex)>0){ //значит есть подкатегории
	        	$tree[$i] = $this->getTree($cat->id,$tree[$i]);
	        	$tree[$i]['name'] = $cat->name;
        	}
        	$i++;
        }
        return $tree;
    }
    public function route($router){ // поиск категории и товаров в строке URI
        $i=1;
        $res = false;
        while(isset($router[$i])){
            if(is_numeric($router[$i])){ //если число
                $res['idItem'] = $router[$i];
            }else{
                $res['idCats'][] = $router[$i];
            }
            $i++;
        }
        return $res;
    }
    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'addCat', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить категорию');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>