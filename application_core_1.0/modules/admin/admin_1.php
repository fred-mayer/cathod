<?php

class Tadmin_admin extends TBAdmin
{
    // Функция добавления модуля на страницу
    public function addmodule( $get, $post )
    {
        $this->insertPageModule( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $get->pages );

        $m_obj = $this->getModule( $get->idmodule->int() );


        $this->displayModuleX( $m_obj->id, $m_obj->name, $m_obj->params, $get->set_pos );
        
        
        /*
        $level = isset($get->level) ? $get->level->int() : 0;

        if ( $get->pages->__toString() == 'all' )
        {
            $result = $this->insertPageModules( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $level );
        }
        else
        {
            $result = $this->insertPageModule( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $level );
        }

        if ( $result ) // Добавлене прошло удачно
        {
            $this->displayModule( $get->idmodule->int(), $get->set_pos );
        }
        else
        {
            echo 'Error admin addmodule';
        }*/
    }
    
    public function delmodule( $get, $post )
    {
        $cpm = $this->db->select( 'SELECT id FROM core_page_modules 
                                        WHERE '.($post->pages->__toString() == 'all' ? '' : 'idpage='.$get->idpage->int().' AND ').'
                                            idmodule='.$get->idmodule->int().' 
                                            AND set_pos=\''.$get->set_pos.'\'' );
        
        foreach ( $cpm as $row )
        {
            $this->db->query( 'DELETE FROM core_page_modules WHERE id='.$row->id );
        }
    }
    
    public function hidemodule( $get, $post )
    {
        $this->db->update( 'core_page_modules', array('hide'=>'hide'), 
                'idpage='.$get->idpage->int().' AND idmodule='.$get->idmodule->int().' AND set_pos=\''.$get->set_pos.'\'' );
    }
    
    public function showmodule( $get, $post )
    {
        $this->db->update( 'core_page_modules', array('hide'=>'show'), 
                'idpage='.$get->idpage->int().' AND idmodule='.$get->idmodule->int().' AND set_pos=\''.$get->set_pos.'\'' );
    }
    
    public function newmodule( $get, $post )
    {
        $m = $this->getModule( $get->idmodule->int() );

        $m_obj = $this->parent->getModule( $m->name ); // Загружаем модуль и задаем ему позицию

        // Заполняем модуль
        $params = $m_obj->admin->insert( $post );
        
        if($params!==false){ //если модуль не вернул строго "нет" тогда все делаем иначе нет смысла!
	        // Создаем новый модуль
	        $idmodule = $this->insertModule( $post->name_module, $m->name, json_encode( $params ) );
	
	        // Устанавливаем на стриницу
	        $this->insertPageModule( $get->idpage->int(), $idmodule, $get->set_pos, $post->pages );
	
	
	        $this->displayModuleX( $idmodule, $m->name, $params, $get->set_pos );
        }
    }
/*
    public function levelmodule( $get, $post,  $mysql, $idanswer, $level )
    {
        $l = $this->db->select( 'SELECT level FROM core_page_modules WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' )->current( 'level' );


        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM core_page_modules WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\'' )->current( 'maxlevel' );


        if ( ($level >= 1) and ($level <= $maxlevel) )
        {
            $this->db->query( 'UPDATE core_page_modules SET level=level+1 WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\' AND level>='.$level.' AND level<'.$l );
            $this->db->query( 'UPDATE core_page_modules SET level='.$level.' WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' );
        }
        elseif ( $maxlevel != $l )
        {
            $mysql->query( "UPDATE answer SET level=level-1 WHERE idquestion=$idquestion AND level<=$maxlevel AND level>$l" );
            $mysql->query( "UPDATE answer SET level=$maxlevel WHERE id=$idanswer" );
        }
    }
*/


    public function newpage( $get, $post )
    {
        $this->db->insert( 'core_page', array('alias'=>$post->alias, 'template'=>$post->template, 'title'=>$post->title, 'keywords'=>$post->keywords,
                                              'descripion'=>$post->descripion, 'script'=>$post->script, 'style'=>$post->style, 
                                              'hide'=>($post->hide == '' ? 'show' : $post->hide)) );
    }

    public function copypage( $get, $post )
    {
        $idpage = $this->db->insert( 'core_page', array('alias'=>$post->alias, 'template'=>$post->template, 'title'=>$post->title, 'keywords'=>$post->keywords,
                                                        'descripion'=>$post->descripion, 'script'=>$post->script, 'style'=>$post->style, 
                                                        'hide'=>($post->hide == '' ? 'show' : $post->hide)) );


        $cpm = $this->db->select( 'SELECT * FROM core_page_modules WHERE idpage='.$get->idpage->int() );
        foreach ( $cpm as $row )
        {
            $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$row->idmodule, 'set_pos'=>$row->set_pos, 'hide'=>$row->hide) );
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



    public function getModule( $idmodule )
    {
        return $this->db->select( 'SELECT m.id, m.name, m.params FROM core_modules m WHERE m.id='.$idmodule )->current();
    }
    
    protected function displayModule( $idmodule, $set_pos )
    {
        $module = $this->getModule( $idmodule );

        if ( !is_array($module->params) )
            $params = ($module->params == '') ? '' : json_decode( $module->params, true );

        $m_obj = $this->parent->getModule( $module->name, $params ); // Загружаем модуль и задаем ему позицию
        $m_obj->idmodule = $module->id;
        $m_obj->set_pos = $set_pos;

        $m_obj->display( $this->parent->template );
    }
    
    
    protected function displayModuleX( $id, $name, $params, $set_pos )
    {
        if ( !is_array($params) )
            $params = ($params == '') ? '' : json_decode( $params, true );

        $m_obj = $this->parent->getModule( $name, $params ); // Загружаем модуль и задаем ему позицию
        $m_obj->idmodule = $id;
        $m_obj->set_pos = $set_pos;

        $m_obj->display( $this->parent->template );
    }

    protected function insertModule( $title, $name, $params )
    {
        return $this->db->insert( 'core_modules', array('title'=>$title, 'name'=>$name, 'params'=>$params) );
    }
    
    
    protected function insertPage( $alias, $template, $title, $keywords, $descripion, $script, $style, $hide )
    {
        return $this->db->insert( 'core_page', array('alias'=>$alias, 'template'=>$template, 'title'=>$title, 'keywords'=>$keywords,
                                                     'descripion'=>$descripion, 'script'=>$script, 'style'=>$style, 
                                                     'hide'=>($hide == '' ? 'show' : $hide)) );
    }
    
    
    protected function insertPageModule( $idpage, $idmodule, $set_pos, $pages )
    {
        if ( $pages->__toString() == 'all' )
        {
            $cpm = $this->db->select( 'SELECT id FROM core_page' );

            foreach ( $cpm as $row )
            {
                $this->db->insert( 'core_page_modules', array('idpage'=>$row->id, 'idmodule'=>$idmodule, 'set_pos'=>$set_pos) );
            }
        }
        else
            $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$idmodule, 'set_pos'=>$set_pos) );
    }
    
    // Фнкция добавляет модуль на страницу idpage
    protected function insertPageModule1( $idpage, $idmodule, $set_pos, $level=0 ) // При успешном выполнение функция вернет true, иначе false
    {
        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM core_page_modules 
                                            WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\'' )->current( 'maxlevel' );


        if ( ($result = $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$idmodule, 'set_pos'=>$set_pos, 'level'=>$maxlevel+1) )) !== false )
        {
            if ( $level > 0 && $level < $maxlevel+1 )
            {
                $this->setLevel( $idpage, $idmodule, $set_pos, $level );
            }
        }
        
        return $result;
    }

    // Фнкция добавляет модуль на все страници включая idpage=0
    protected function insertPageModules( $idpage, $idmodule, $set_pos, $level=0 ) // При успешном выполнение функция вернет true, иначе false
    {
        // Начинаем транзакцию
        
        $this->insertPageModule( 0, $idmodule, $set_pos );


        $pages = $this->db->select( 'SELECT id FROM core_page' );

        foreach ( $pages as $row )
        {
            if ( $row->id == $idpage )
                $this->insertPageModule( $row->id, $idmodule, $set_pos, $level );
             else
                $this->insertPageModule( $row->id, $idmodule, $set_pos );
        }
        
        // Проверяем и фиксируем транзакцию
    }
}

?>