<?php

class Ttwitter_profile extends TModule
{
    public function display( TTemplate $template )
    {
        if ( $template->route[0] == 'profile' && $this->set_pos == 'section' ) // Главный модуль
        {
            if ( !empty($template->route[1]) )
            {
                $this->data['profile'] = $template->db->select( 'SELECT * FROM twitter_profile WHERE nickname=\''.$template->route[1].'\'' )->current();

                
                if ( $this->data['profile'] === false )
                {
                    $template->_404();
                }


                $template->setTitle( 'Чикенхиро '.$this->data['profile']->name );


                $this->data['twitter'] = $template->db->select( 'SELECT * FROM twitter WHERE idtwitter_profile='.$this->data['profile']->id.' ORDER BY date DESC' );
                
                $this->idprofile = $this->data['profile']->id;
            }
            elseif ( $template->auth->isAdmin )
            {
                $this->data['profile'] = $template->db->select( 'SELECT * FROM twitter_profile ORDER BY name' );
            }
            else
            {
                $template->location();
            }
        }
        
        parent::display( $template );
    }

    public function getAdminToolbar( $attr )
    {
        if ( !empty($this->idprofile) )
        {
            $buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'', 'title'=>'Редактировать профиль', 'more'=>array('idprofile'=>$this->idprofile));
            $buttons[] = array('action'=>'del', 'icon'=>'remove', 'text'=>'', 'title'=>'Удалить профиль', 'more'=>array('idprofile'=>$this->idprofile));
        }
        else
        {
            $buttons[] = array('action'=>'add', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить новый профиль');
        }

        parent::getAdminToolbar( $attr, $buttons );
        
        return false;
    }
}

?>