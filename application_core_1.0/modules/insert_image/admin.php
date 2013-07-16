<?php

class Tadmin_insert_image extends TBAdmin
{
    public function upload( $get, $post )
    {
        if ( isset($_FILES['img_file']) )
        {
            $file = $_FILES['img_file'];
            $tmp_filename = $file['tmp_name'];
            
            if ( is_uploaded_file( $tmp_filename ) )
            {
                $info = GetImageSize( $tmp_filename );
                
                if ( $info[2] == IMAGETYPE_JPEG || $info[2] == IMAGETYPE_PNG || $info[2] == IMAGETYPE_GIF )
                {
                    if ( move_uploaded_file( $tmp_filename, 'media/images/'.$file['name'] ) )
                        echo SERVER_NAME.'/media/images/'.$file['name'];
                    else
                        echo 'Ошибка!';
                }
            }
        }
    }
}

?>