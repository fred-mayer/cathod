<?php

class TModule_admin extends TInstall_admin
{
    // Функция создает новый модуль и добавляет на страницу
    public function newmodule( $get, $post )
    {
        $m = $this->getModuleGroupById( $get->idmodule->int() );

        $m_obj = $this->parent->getModule( $m->name ); // Загружаем модуль и задаем ему позицию
        // Заполняем модуль
        $params = $m_obj->admin->insert( $post );
        
        if ( $params !== false ) //если модуль не вернул строго "нет" тогда все делаем иначе нет смысла!
        {
            // Создаем новый модуль
	    $idmodule = $this->insertModule( $post->name_module, $m->name, json_encode( $params ) );
	
            
            if ( $post->pages->__toString() == 'all' )
            {
                $result = $this->insertPageModules( $get->idpage->int(), $idmodule, $get->set_pos );
            }
            else
            {
                $result = $this->insertPageModule( $get->idpage->int(), $idmodule, $get->set_pos );
            }

            if ( $result !== false ) // Добавлене прошло удачно
            {
                $this->result['content'] = $this->displayModule( $idmodule, $get->set_pos );
            }
            else
            {
                $this->result['error'] = 'Error admin newmodule #2';
            }
        }
        else
        {
            $this->result['error'] = 'Error admin newmodule #1';
        }
    }

    // Функция добавления модуля на страницу
    public function addmodule( $get, $post )
    {
        $level = isset($get->level) ? $get->level->int() : 0;


        if ( isset($get->idmodule_group) )
        {
            $module_name = $this->db->select( 'SELECT name FROM core_modules_group WHERE id='.$get->idmodule_group->int() )->current( 'name' );
            $idmodule = $this->db->select( 'SELECT id FROM core_modules WHERE exist=1 AND name=\''.$module_name.'\'' )->current( 'id' );
        }
        else
            $idmodule = $get->idmodule->int();


        if ( $get->pages->__toString() == 'all' )
        {
            $result = $this->insertPageModules( $get->idpage->int(), $idmodule, $get->set_pos, $level );
        }
        else
        {
            $result = $this->insertPageModule( $get->idpage->int(), $idmodule, $get->set_pos, $level );
        }

        if ( $result !== false ) // Добавлене прошло удачно
        {
            $this->result['content'] = $this->displayModule( $idmodule, $get->set_pos );
        }
        else
        {
            $this->result['error'] = 'Error admin addmodule';
        }
    }

    //
    public function delmodule( $get, $post )
    {
        $this->db->update( 'core_page_modules', array('hide'=>'hide'), 
                ($post->pages->__toString() == 'all' ? '' : 'idpage='.$get->idpage->int().' AND ').' 
                    idmodule='.$get->idmodule->int().' 
                        AND set_pos=\''.$get->set_pos.'\'' );
    }
    
    //
    public function setlevelmodule( $get, $post )
    {
        $this->setLevel( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $get->level );
    }
    
    public function upmodule( $get, $post )
    {
        $module = $this->getPageModule( $get->idpage->int(), $get->idmodule->int(), $get->set_pos );

        $this->setLevel( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $module->level-1 );
    }
    
    public function downmodule( $get, $post )
    {
        $module = $this->getPageModule( $get->idpage->int(), $get->idmodule->int(), $get->set_pos );

        $this->setLevel( $get->idpage->int(), $get->idmodule->int(), $get->set_pos, $module->level+1 );
    }


    public function getModuleById( $idmodule )
    {
        return $this->db->select( 'SELECT * FROM core_modules WHERE id='.$idmodule )->current();
    }
    
    public function getModuleGroupById( $idmodule )
    {
        return $this->db->select( 'SELECT name, version, title, description FROM core_modules_group WHERE id='.$idmodule )->current();
    }

    public function getAllModules()
    {
        return $this->db->select( 'SELECT * FROM core_modules_group' );
    }


    private function getPageModule( $idpage, $idmodule, $set_pos )
    {
        return $this->db->select( 'SELECT * FROM core_page_modules WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' )->current();
    }

    private function displayModule( $idmodule, $set_pos )
    {
        $module = $this->getModuleById( $idmodule );

        if ( !is_array($module->params) )
            $params = ($module->params == '') ? '' : json_decode( $module->params, true );

        $m_obj = $this->parent->getModule( $module->name, $params ); // Загружаем модуль и задаем ему позицию
        $m_obj->idmodule = $module->id;
        $m_obj->set_pos = $set_pos;

        return $this->template->displayModule( $m_obj );//$m_obj->display( $this->parent->template );
    }

    private function insertModule( $title, $name, $params )
    {
        return $this->db->insert( 'core_modules', array('title'=>$title, 'name'=>$name, 'params'=>$params) );
    }

    // Фнкция добавляет модуль на страницу idpage
    private function insertPageModule( $idpage, $idmodule, $set_pos, $level=0 ) // При успешном выполнение функция вернет true, иначе false
    {
        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM core_page_modules 
                                            WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\'' )->current( 'maxlevel' );

        $module = $this->getPageModule( $idpage, $idmodule, $set_pos );
        if ( $module !== false && $module->hide == 'hide' )
        {
            $result = $this->db->update( 'core_page_modules', array('hide'=>'show'), 'idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' );
            if ( $result !== false )
                $this->setLevel( $idpage, $idmodule, $set_pos, $maxlevel );
            
            return $result;
        }


        $result = $this->db->insert( 'core_page_modules', array('idpage'=>$idpage, 'idmodule'=>$idmodule, 'set_pos'=>$set_pos, 'level'=>$maxlevel+1) );

        if ( $result !== false )
        {
            if ( $level > 0 && $level < $maxlevel+1 )
            {
                $this->setLevel( $idpage, $idmodule, $set_pos, $level );
            }
        }
        
        return $result;
    }

    // Фнкция добавляет модуль на все страници включая idpage=0
    private function insertPageModules( $idpage, $idmodule, $set_pos, $level=0 ) // При успешном выполнение функция вернет true, иначе false
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


    private function setLevel( $idpage, $idmodule, $set_pos, $level )
    {
        $l = intval( $this->db->select( 'SELECT level FROM core_page_modules 
                                    WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' )->current( 'level' ) );


        $maxlevel = $this->db->select( 'SELECT MAX(level) AS maxlevel FROM core_page_modules 
                                            WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\'' )->current( 'maxlevel' );


        if ( $level > $maxlevel ) $level = $maxlevel;
        if ( $level < 1 ) $level = 1;


        if ( $level < $l )
        {
            $this->db->query( 'UPDATE core_page_modules SET level=level+1 
                                    WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\' AND level>='.$level.' AND level<'.$l );

            $this->db->query( 'UPDATE core_page_modules SET level='.$level.' 
                                    WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' );
        }
        elseif ( $level > $l )
        {
            $this->db->query( 'UPDATE core_page_modules SET level=level-1 
                                    WHERE idpage='.$idpage.' AND set_pos=\''.$set_pos.'\' AND level<='.$level.' AND level>'.$l );

            $this->db->query( 'UPDATE core_page_modules SET level='.$level.' 
                                    WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' );
        }
    }
}

?>