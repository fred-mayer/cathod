<?php

class Tadmin_twitter_profile extends TBAdmin
{
    public function get( $idprofile )
    {
        return $this->db->select( 'SELECT * FROM twitter_profile WHERE id='.$idprofile )->current();
    }
    
    public function getpost( $idpost )
    {
        return $this->db->select( 'SELECT * FROM twitter WHERE id='.$idpost )->current();
    }
    
    public function add( $get, $post )
    {
        $img = '';

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
                    
                    $img = $post->nickname.'.'.$e;
                    
                    move_uploaded_file( $tmp_filename, 'media/profile/'.$img );
                    
                    
                    $this->resizeToRectImage( 'media/profile/'.$img, '194', '194' );
                }
            }
        }
        
        
        
        $id = $this->db->insert( 'twitter_profile', array('name'=>$post->name, 'nickname'=>$post->nickname, 'img'=>$img) );
        echo '<img src="/media/profile/'.$img.'"><a href="/profile/'.$post->nickname.'">'.$post->name.'</a>';
    }
    
    public function addpost( $get, $post )
    {
        //var_dump($get);
        //var_dump($post);
        
        $this->db->insert( 'twitter', array('idtwitter_profile'=>$get->idprofile->int(), 'post'=>$post->post) );
    }
    
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
                    //if ( $info[2] == IMAGETYPE_GIF ) $e = 'gif';
                    
                    $img = $post->nickname.'.'.$e;
                    
                    move_uploaded_file( $tmp_filename, 'media/profile/'.$img );
                    
                    
                    $this->resizeToRectImage( 'media/profile/'.$img, '194', '194' );
                }
            }
        }
        
        $par = array('name'=>$post->name, 'nickname'=>$post->nickname);
            
        if ( isset($img) ) $par['img'] = $img;
        
        $this->db->update( 'twitter_profile', $par, 'id='.$post->idprofile->int() );
    }
    
    public function editpost( $get, $post )
    {
        //var_dump($get);
        //var_dump($post);
        
        $this->db->update( 'twitter', array('post'=>$post->post), 'id='.$get->idpost->int() );
    }
    
    public function del( $get, $post )
    {
        $this->db->query( 'DELETE FROM twitter_profile WHERE id='.$get->idprofile->int() );
        $this->db->query( 'DELETE FROM twitter WHERE idtwitter_profile='.$get->idprofile->int() );
    }
    
    public function delpost( $get, $post )
    {
        $this->db->query( 'DELETE FROM twitter WHERE id='.$get->id->int() );
    }
    
    
    protected function resizeToRectImage( $file, $width, $height )
    {
        $info = GetImageSize( $file );
        
        if ( $info[2] == IMAGETYPE_JPEG )
        {
            $img = ImageCreateFromJPEG( $file );
        }
        elseif( $info[2] == IMAGETYPE_PNG )
        {
            $img = ImageCreateFromPNG( $file );
        }
        else
        {
            return false;
        }
        
        
        $srcW = ImageSX( $img );
        $srcH = ImageSY( $img );
        $srcX = ( $srcW - ( $srcH * ($width / $height) ) ) / 2;
        $srcY = 0;

        $newimage = ImageCreateTrueColor( $width, $height );
        ImageCopyResampled( $newimage, $img, 0, 0, $srcX, $srcY, $width, $height, $srcW - $srcX * 2, $srcH );

        ImageDestroy( $img );
        
        
        if ( $info[2] == IMAGETYPE_JPEG )
        {
            return ImageJPEG( $newimage, $file, 95 );
        }
        elseif( $info[2] == IMAGETYPE_PNG )
        {
            return ImagePNG( $newimage, $file );
        }
    }
}

?>