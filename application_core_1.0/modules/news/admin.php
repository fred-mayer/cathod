<?php

class Tadmin_news extends TBAdmin
{
    public function get( $idmodule )
    {
        $params = $this->db->select( 'SELECT params FROM core_modules WHERE id='.$idmodule )->current('params');
        return ($params == '') ? array() : json_decode( $params, true );
    }
    
    public function getParse()
    {
        return $this->db->select( 'SELECT * FROM parser_new' );
    }
    
    public function getParseById( $id )
    {
        return $this->db->select( 'SELECT * FROM parser_new WHERE id='.$id )->current();
    }
    
    public function editparser( $get, $post )
    {
        if ( isset($get->id) )
        {
            $this->db->update( 'parser_new', array('content'=>$post->content, 'title'=>$post->title, 'link'=>$post->link, 
                                                   'img'=>$post->img, 'description'=>$post->description, 'text'=>$post->text, 
                                                   'site'=>$post->site, 'parser'=>($post->parser == '' ? 'off' : $post->parser)), 'id='.$get->id->int() );
        }
        else
        {
            $this->db->insert( 'parser_new', array('content'=>$post->content, 'title'=>$post->title, 'link'=>$post->link, 
                                                   'img'=>$post->img, 'description'=>$post->description, 'text'=>$post->text, 
                                                   'site'=>$post->site, 'parser'=>($post->parser == '' ? 'off' : $post->parser)) );
        }
    }

    public function edit( $get, $post )
    {
        $params = array('limit' => $post->limit->int(), 'img'=>$post->img->__toString()); 
        $this->db->update( 'core_modules', array('params'=>json_encode( $params )), 'id='.$get->idmodule->int() );
        
        $this->parent->setParams($params);
        $this->parent->display($this->parent->template);
    }
    
    
    // Обезательно, автоматом вызывается из модуля админ
    public function insert( $post )
    {
        return array('limit' => $post->limit->int(), 'img'=>$post->img->__toString());
    }
}

?>