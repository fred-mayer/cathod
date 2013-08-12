<?php

class TPage_admin extends TModule_admin
{
    public function newpage( $get, $post )
    {
        if ( ($idpage = $this->insertPage( $post->alias, $post->template, $post->title, $post->keywords, 
                                           $post->descripion, $post->script, $post->style, $post->hide )) === false )
        {
            echo 'Error newpage';
        }
        else
        {
            $pages = $this->db->select( 'SELECT * FROM core_page_modules WHERE idpage=0' );
            foreach ( $pages as $row )
            {
                $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$row->idmodule, 'set_pos'=>$row->set_pos, 
                                                              'hide'=>$row->hide, 'level'=>$row->level) );
            }
        }
    }

    public function copypage( $get, $post )
    {
        if ( ($idpage = $this->insertPage( $post->alias, $post->template, $post->title, $post->keywords, 
                                           $post->descripion, $post->script, $post->style, $post->hide )) === false )
        {
            echo 'Error newpage';
        }
        else
        {
            $pages = $this->db->select( 'SELECT * FROM core_page_modules WHERE idpage='.$get->idpage->int() );
            foreach ( $pages as $row )
            {
                $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$row->idmodule, 'set_pos'=>$row->set_pos, 
                                                              'hide'=>$row->hide, 'level'=>$row->level) );
            }
        }
    }

    public function editpage( $get, $post )
    {
        $this->db->update( 'core_page', array('alias'=>$post->alias, 'template'=>$post->template, 'title'=>$post->title, 'keywords'=>$post->keywords,
                                              'descripion'=>$post->descripion, 'script'=>$post->script, 'style'=>$post->style, 
                                              'hide'=>($post->hide == '' ? 'show' : $post->hide)), 'id='.$get->idpage->int() );
    }

    public function hidepage( $get, $post )
    {
        
    }

    public function delpage( $get, $post )
    {
        
    }


    private function insertPage( $alias, $template, $title, $keywords, $descripion, $script, $style, $hide )
    {
        return $this->db->insert( 'core_page', array('alias'=>$alias, 'template'=>$template, 'title'=>$title, 'keywords'=>$keywords,
                                                     'descripion'=>$descripion, 'script'=>$script, 'style'=>$style, 
                                                     'hide'=>($hide == '' ? 'show' : $hide)) );
    }
}

?>