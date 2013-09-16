<?php

class Tadmin_admin extends TPage//TBAdmin
{
    static public $newmodule;
    // Функция создает новый модуль и добавляет на страницу
    public function newmodule( $get, $post )
    {
        $res = array();
        
        $m = $this->getModule( $get->idmodule->int() );

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
                $res['content'] = $this->displayModule( $idmodule, $get->set_pos );
            }
            else
            {
                $res['error'] = 'Error admin newmodule #2';
            }
        }
        else
        {
            $res['error'] = 'Error admin newmodule #1';
        }
        
        echo json_encode( $res );
    }

    // Функция добавления модуля на страницу
    public function addmodule( $get, $post )
    {
        $res = array();
        

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
            $res['content'] = $this->displayModule( $idmodule, $get->set_pos );
        }
        else
        {
            $res['error'] = 'Error admin addmodule';
        }
        
        echo json_encode( $res );
    }

    
    public function delmodule( $get, $post )
    {
        /*$this->db->update( 'core_page_modules', array('hide'=>'hide'), 
                ($post->pages->__toString() == 'all' ? '' : 'idpage='.$get->idpage->int().' AND ').' 
                    idmodule='.$get->idmodule->int().' 
                        AND set_pos=\''.$get->set_pos.'\'' );*/
    }
    
    public function showmodule( $get, $post )
    {
        $this->db->update( 'core_page_modules', array('hide'=>'show'), 
                ($post->pages->__toString() == 'all' ? '' : 'idpage='.$get->idpage->int()).' 
                    AND idmodule='.$get->idmodule->int().' 
                        AND set_pos=\''.$get->set_pos.'\'' );
    }
    

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
    
    
    public function installmodule( $get, $post )
    {
        // Читаем архив
        $zip = new ZipArchive;
        if ( $zip->open( TMP_DIR.'coupons_0.1.zip' ) === true ) 
        {
            // Находим файл install.txt - читаем и декодируем json
            if ( ($install_json = $zip->getFromName( '/install.txt' )) === false )
            {
                // error ненайден install.txt
                echo 'error ненайден install.txt<br>';
                return;
            }
            
            $install = new TObject();
            $install->decode_json( $install_json );
            
            
            // Проверяем установлен такой модуль проверка по имени и версии
            if ( !$this->db->exists( 'SELECT id FROM core_modules_group WHERE name=\''.$install->name.'\' AND version=\''.$install->version.'\'' ) )
            {
                if ( !mkdir( MODULES_DIR.$install->name.'_'.$install->version.'/' ) )
                {
                    // error не удалось создать каталог
                    echo 'error не удалось создать каталог '.MODULES_DIR.$install->name.'_'.$install->version.'/<br>';
                    return;
                }


                // извлекаем содержимое архива
                for ( $i = 0; $i < $zip->numFiles; $i++ )
                {
                    $file = $zip->getNameIndex( $i );
                    
                    if ( $file != '/install.txt' && $file != '/db.sql' )
                        $zip->extractTo( MODULES_DIR.$install->name.'_'.$install->version.'/', $file );
                }


                // Выполняем запросы из файла db.sql
                if ( ($db_sql = $zip->getFromName( '/db.sql' )) !== false )
                {
                    $this->db->multi_query( $db_sql );
                }

                
                // Добавляем в базу свединия об установленом модуле
                $this->db->query( 'INSERT INTO core_modules_group (title, descripion, name, version, tables, exist) 
                                        VALUES (\''.$install->title.'\', \''.$install->descripion.'\', \''.$install->name.'\', 
                                                \''.$install->version.'\', \''.$install->tables.'\', '.$install->exist.') 
                                            ON DUPLICATE KEY UPDATE version=\''.$install->version.'\', date=\''.date('Y-m-d H:i:s').'\'' );
                
                $this->db->query( 'INSERT INTO core_modules (title, name, params, exist) 
                                        VALUES (\''.$install->title.'\', \''.$install->name.'\', \'\', 1)' );
            }
            else
            {
                echo 'error Этот модуль уже установлен';
            }
            
            $zip->close();
        }
        else
        {
            echo 'error Неудалось открыть архив';
        }

        echo 'ok';
    }

    public function uninstallmodule( $get, $post )
    {
        // Читаем свединия о модуле
        $module = $this->db->select( 'SELECT title, descripion, name, version, tables, exist FROM core_modules_group WHERE id='.$get->id->int() )->current();
        
        // Удаляем все созданые таблици при установке модуля
        
        // Удаляем из core_modules
        
        // Удаляем из core_modules_group
        $this->db->select( 'DELETE FROM core_modules_group WHERE id='.$get->id->int() );
        
        // Удаляем папку
    }

    public function importmodule( $get, $post )
    {
        // Читаем свединия о модуле
        $module = $this->db->select( 'SELECT title, descripion, name, version, tables, exist FROM core_modules_group WHERE id='.$get->id->int() )->current();
        
        // Добавляем файлы в архив
        $zip = new ZipArchive;
        if ( $zip->open( TMP_DIR.$module->name.'_'.$module->version.'.zip', ZipArchive::CREATE ) === true )
        {
            $this->addFolderToZip( MODULES_DIR.$module->name.'/', '', $zip );
            
            // Добавляем файл install.txt и db.sql
            $zip->addFromString( '/install.txt', $module );
            $zip->addFromString( '/db.sql', $this->importTable( $module->tables ) );
            $zip->close();
        }
        
        echo 'ok';
    }

    
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



    public function getModule( $idmodule )
    {
        return $this->db->select( 'SELECT m.id, m.name, m.params, g.version 
                                        FROM core_modules m, core_modules_group g 
                                        WHERE m.name=g.name AND m.id='.$idmodule )->current();
    }

    public function getAllModules()
    {
        return $this->db->select( 'SELECT * FROM core_modules_group' );
    }


    protected function getPageModule( $idpage, $idmodule, $set_pos )
    {
        return $this->db->select( 'SELECT * FROM core_page_modules WHERE idpage='.$idpage.' AND idmodule='.$idmodule.' AND set_pos=\''.$set_pos.'\'' )->current();
    }

    protected function displayModule( $idmodule, $set_pos )
    {
        $module = $this->getModule( $idmodule );

        if ( !is_array($module->params) )
            $params = ($module->params == '') ? '' : json_decode( $module->params, true );

        $m_obj = $this->parent->getModule( $module->name, $params ); // Загружаем модуль и задаем ему позицию
        $m_obj->idmodule = $module->id;
        $m_obj->set_pos = $set_pos;

        return $this->template->displayModule( $m_obj );//$m_obj->display( $this->parent->template );
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

    // Фнкция добавляет модуль на страницу idpage
    protected function insertPageModule( $idpage, $idmodule, $set_pos, $level=0 ) // При успешном выполнение функция вернет true, иначе false
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


    protected function setLevel( $idpage, $idmodule, $set_pos, $level )
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
    
    
    protected function addFolderToZip( $src_dir, $desc_dir, $zip )
    {
        if ( is_dir( $src_dir ) )
        {
            if ( ($dh = opendir( $src_dir )) !== false )
            {
                $zip->addEmptyDir( $desc_dir );

                while ( ($file = readdir( $dh )) !== false )
                {
                    if ( !is_file( $src_dir.$file ) )
                    {
                        if ( ($file !== '.') && ($file !== '..') )
                        {
                            $this->addFolderToZip( $src_dir.$file.'/', $desc_dir.$file.'/', $zip);
                        }
                    }
                    else
                    {
                        $zip->addFile( $src_dir.$file, $desc_dir.$file );
                    }
                }
            }
        }
    }
    
    protected function importTable( $name )
    { 
        $tables = explode( ';', $name );
        $content = '';
        
        foreach ( $tables as $name )
        {
            $result = $this->db->select( 'SHOW CREATE TABLE `'.$name.'`;' )->current('Create Table');
            //$content .= 'DROP TABLE IF EXISTS `'.$name."`;\n\n";//CREATE TABLE IF NOT EXISTS
            //$content .= $result.";\n\n";
            $content .= preg_replace( '/^CREATE TABLE/ui', 'CREATE TABLE IF NOT EXISTS', $result );
        }
        
        return $content;
    }
}

?>