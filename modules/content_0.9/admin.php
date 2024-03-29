<?php

class Tadmin_content extends TBAdmin
{
    public function getContent( $idmodule )
    {
        $id = $this->getIdContent( $idmodule );

        return $this->db->select( 'SELECT id, content FROM content WHERE id='.$id )->current();
    }
    
    public function edit( $get, $post )
    {
        $id = $this->getIdContent( $get->idmodule->int() );
        
        if($post->isHTML=="1"){
	        $content = $post->html;
        }else{
	        $content = $post->content;
        }
        
        $this->db->update( 'content', array('content'=>$content), 'id='.$id );
        
        echo $post->content;
    }
    
    public function insert( $post )
    {
    	if($post->isHTML=="1"){
	        $content = $post->html;
        }else{
	        $content = $post->content;
        }
        $id = $this->db->insert( 'content', array('content'=>$content) );
        
        return array( 'id'=>$id );
    }
    
    protected function getIdContent( $idmodule )
    {
        $params = json_decode( $this->db->select( 'SELECT params FROM core_modules WHERE id='.$idmodule )->current('params'), true );
        
        return $params['id'];
    }
}

?>