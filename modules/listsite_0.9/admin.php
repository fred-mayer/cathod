<?php

class Tadmin_listsite extends TBAdmin
{
    public function edit( $get, $post )
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
                    
                    $logo = '';
                    if ( preg_match( '/^http\:\/\/(.*?)\/.*/i', $post->site, $matches ) )
                    {
                        $logo = $matches[1].'.'.$e;
                        
                    }
                    else
                    {
                        $logo = $post->site.'.'.$e;
                    }
                    
                    move_uploaded_file( $tmp_filename, 'media/logos/'.$logo );
                }
            }
        }
        
        
        if ( isset($get->idmodule) )
        {
            $id = $get->idmodule->int();
            
            $par = array('site'=>$post->site, 'url'=>$post->url, 'descripion'=>$post->descripion, 'descripion_all'=>$post->descripion_all);
            
            if ( isset($logo) ) $par['logo'] = $logo;

            
            $this->db->update( 'listsite', $par, 'id='.$id );
        }
        else
        {

            $id = $this->db->insert( 'listsite', array('site'=>$post->site, 
                                                       'url'=>$post->url,
                                                       'descripion'=>$post->descripion,
                                                       'descripion_all'=>$post->descripion_all,
                                                       'logo'=>$logo) );
        }
    }
    
    public function insert( $post )
    {
        
    }
    
    public function del( $get, $post )
    {
        if ( isset($get->idmodule) )
        {
            $this->db->query( 'DELETE FROM listsite WHERE id='.$get->idmodule->int() );
        }
    }
}

?>