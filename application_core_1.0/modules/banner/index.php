<?php

class Tbanner extends TModule
{
    public function getBannerById( $id )
    {
        return $this->db->select( 'SELECT id, src, href, level FROM banner WHERE id='.$id )->current();
    }

    public function setBanners()
    {
        // Получаем все поля из базы
        return $this->db->select( 'SELECT id, src, href, level FROM banner ORDER BY level' );
    }
    
    public function display( TTemplate $template )
    {
        $params = $this->getParams();
        if ( is_array($params) ) extract( $params );


        /*if ( !isset($category) && isset($template->route[1]) ) // если категория не задана берем из url
        {
            if ( isset($template->route[1]) )
                $category = 'category=\''.$template->route[1].'\''; 
        }*/

        
        $where = '';
        if ( isset($category) )
            $where .= ' WHERE '.$category;

        // Получаем все поля из базы
        $this->data = $this->db->select( 'SELECT id, src, href, level FROM banner'.$where.' ORDER BY level LIMIT 6' );

        parent::display( $template );
    }
    
    public function add( $get, $post )
    {
        var_dump($get);
        var_dump($post);
        var_dump( $_FILES);
    }
    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'', 'title'=>'редактировать');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>