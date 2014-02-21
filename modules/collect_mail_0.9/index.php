<?php

class Tcollect_mail extends TModule
{
    public function display( TTemplate $template )
    {
        /*$params = $this->getParams();

        if ( isset($params['alias']) )
            $where = 'alias=\''.$params['alias'].'\'';
        elseif ( isset($params['id']) )
            $where = 'id='.$params['id'];

        $this->data = $template->db->select( 'SELECT id, content FROM content WHERE '.$where )->current();

        parent::display( $template );*/
    }

    public function getAdminToolbar( $attr, $buttons=null )
    {
        /*$buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'', 'title'=>'');
        
        return parent::getAdminToolbar( $attr, $buttons );*/
    }
    
    public function submit( $get, $post )
    {
        //var_dump($post);
        
        $this->db->insert( 'collect_mail', array('email'=>$post->email, 'user'=>$post->user) );
    }
}

?>