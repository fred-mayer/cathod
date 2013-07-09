<?php

class Tnews extends TModule
{
    public function display( TTemplate $template )
    {
        if ( !$template->auth->isAdmin && $this->hide == 'hide' ) return;
        
        
        if ( $template->route[0] == $this->getName() && $this->set_pos == 'section' ) // Главный модуль
        {
            if ( isset($template->route[1]) )
            {
                if ( ($id = intval( $template->route[1] )) > 0 ) // Есть id новости
                {
                    $this->data['new'] = $template->db->select( 'SELECT * FROM news WHERE id='.$id )->current();

                    $template->setTitle( $this->data['new']->title ); 
                    
                    return parent::display( $template );
                }
                else
                {
                    $this->data['news'] = $template->db->select( 'SELECT * FROM news WHERE category=\''.$template->route[1].'\' ORDER BY date DESC' );

                    return parent::display( $template );
                }
            }
        }
        
        $params = $this->getParams();

        $where = isset($params['category']) ? ' WHERE category=\''.$params['category'].'\'' : '';
        $limit = isset($this->params['limit']) && $this->params['limit'] !== 0 ? ' LIMIT '.$this->params['limit'] : ' LIMIT 10';

        $this->data['news'] = $template->db->select( 'SELECT * FROM news'.$where.' ORDER BY date DESC'.$limit );


        parent::display( $template );
    }

    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'', 'title'=>'');
        $buttons[] = array('action'=>'parser', 'icon'=>'asterisk', 'text'=>'Парсер', 'title'=>'');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>