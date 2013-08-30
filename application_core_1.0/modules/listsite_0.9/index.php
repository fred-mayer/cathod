<?php

class Tlistsite extends TModule
{
    public function display( TTemplate $template )
    {
        if ( $template->route[0] == $this->getName() && $this->set_pos == 'section' ) // Главный модуль
        {
            if ( isset($template->route[1]) )
            {
                if ( ($id = intval( $template->route[1] )) > 0 ) // Есть id новости
                {
                    if ( ($this->data['current'] = $template->db->select( 'SELECT * FROM listsite WHERE id='.$id )->current()) === false )
                    {
                        $template->_404();
                    }

                    $template->setTitle( $this->data['current']->site ); 
                    
                    return parent::display( $template );
                }
            }
        }
        
        $this->data['list'] = $template->db->select( 'SELECT * FROM listsite ORDER BY site' );

        parent::display( $template );
    }

    public function getAdminToolbar( $attr, $buttons=null )
    {
        $buttons[] = array('action'=>'edit', 'icon'=>'plus', 'text'=>'', 'title'=>'');
        
        return parent::getAdminToolbar( '', $buttons );
    }
    
    public function getSiteById( $id )
    {
        return $this->db->select( 'SELECT * FROM listsite WHERE id='.$id )->current();
    }
}

?>