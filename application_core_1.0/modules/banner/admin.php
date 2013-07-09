<?php

class Tadmin_banner extends TBAdmin
{
    public function add( $get, $post )
    {
        if ( isset($_FILES['file']) )
        {
            $file = $_FILES['file'];
            $tmp_filename = $file['tmp_name'];
            
            if ( is_uploaded_file( $tmp_filename ) )
            {
                $info = GetImageSize( $tmp_filename );
                
                if ( $info[2] == IMAGETYPE_JPEG || $info[2] == IMAGETYPE_PNG || $info[2] == IMAGETYPE_GIF )
                {
                    if ( $info[2] == IMAGETYPE_JPEG ) $e = 'jpg';
                    if ( $info[2] == IMAGETYPE_PNG ) $e = 'png';
                    if ( $info[2] == IMAGETYPE_GIF ) $e = 'gif';
                    
                    if ( isset($get->id) )
                    {
                        $id = $get->id->int();
                    }
                    else
                    {
                        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM banner' )->current( 'maxlevel' );
                        
                        $id = $this->db->insert( 'banner', array('src'=>$e, 
                                                                 'href'=>$post->href,
                                                                 'category'=>'',
                                                                 'keyword'=>'',
                                                                 'level'=>$maxlevel) );
                        
                        $this->level( $id, $post->level->int() );
                    }
                    
                    move_uploaded_file( $tmp_filename, 'media/banner/banner_'.$id.'.'.$e );
                }
            }
        }
        
        
        if ( isset($get->id) )
        {
            $id = $get->id->int();
            
            $par = array('href'=>$post->href, 'category'=>'', 'keyword'=>'');
            
            if ( isset($e) ) $par['src'] = $e;
            
            $this->db->update( 'banner', $par, 'id='.$id );
                        
            $this->level( $id, $post->level->int() );
        }
    }
    
    public function del( $get, $post )
    {
        $this->db->query( 'DELETE FROM banner WHERE id='.$get->id->int() );
    }
    
    public function edit( $get, $post )
    {
        //$this->db->update( 'content', array('content'=>$post->content), 'id='.$get->id->int() );
    }
    
    public function insert( $post )
    {
        //$id = $this->db->insert( 'content', array('alias'=>$post->alias, 'content'=>$post->content) );
        
        //return array('id' => $id);
    }
    
    
    protected function level( $id, $level )
    {
        $l = $this->db->select( 'SELECT level FROM banner WHERE id='.$id )->current( 'level' );


        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM banner' )->current( 'maxlevel' );
        
        if ( $level > $maxlevel ) $level = $maxlevel;


        if ( ($level < $l) )
        {
            $this->db->query( 'UPDATE banner SET level=level+1 WHERE level>='.$level.' AND level<'.$l );
            $this->db->query( 'UPDATE banner SET level='.$level.' WHERE id='.$id );
        }
        elseif ( ($level > $l) )
        {
            $this->db->query( "UPDATE banner SET level=level-1 WHERE level<=$level AND level>$l" );
            $this->db->query( "UPDATE banner SET level=$level WHERE id=$id" );
        }
        
        
        /*if ( ($level >= 1) and ($level <= $maxlevel) )
        {
            $this->db->query( 'UPDATE banner SET level=level+1 WHERE level>='.$level.' AND level<'.$l );
            $this->db->query( 'UPDATE banner SET level='.$level.' WHERE id='.$id );
        }
        elseif ( $maxlevel != $l )
        {
            $this->db->query( "UPDATE banner SET level=level-1 WHERE level<=$maxlevel AND level>$l" );
            $this->db->query( "UPDATE banner SET level=$maxlevel WHERE id=$id" );
        }*/
    }
}

?>