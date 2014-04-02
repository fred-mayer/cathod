<?php

class TInstall_admin extends TBAdmin
{
    public function installmodule( $get, $post )
    {
        if ( isset($_FILES['file']) )
        {
            $file = $_FILES['file'];
            $tmp_filename = $file['tmp_name'];
            
            if ( is_uploaded_file( $tmp_filename ) )
            {
                // Читаем архив
                $zip = new ZipArchive;
                if ( $zip->open( $tmp_filename ) === true ) 
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

                        if ( $install->exist == 1 )
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
            }
        }

        echo 'ok';
    }

    public function uninstallmodule( $get, $post )
    {
        // Читаем свединия о модуле
        if ( ($module = $this->db->select( 'SELECT id, name, version, tables FROM core_modules_group WHERE id='.$get->id->int() )->current()) !== false )
        {
            // Удаляем все созданые таблици при установке модуля
            $tables = explode( ';', $module->tables );
            foreach ( $tables as $name )
            {
                $this->db->query( 'DROP TABLE `'.$name.'`' );
            }


            // Удаляем из core_page_modules
            $m_obj = $this->db->select( 'SELECT id FROM core_modules WHERE name=\''.$module->name.'\'' );
            foreach ( $m_obj as $m )
            {
                $this->db->query( 'DELETE FROM core_page_modules WHERE idmodule='.$m->id );
            }
            
            
            // Удаляем из core_modules
            $this->db->query( 'DELETE FROM core_modules WHERE name=\''.$module->name.'\'' );

            // Удаляем из core_modules_group
            $this->db->query( 'DELETE FROM core_modules_group WHERE id='.$module->id );

            // Удаляем папку
            $this->removeDirectory( MODULES_DIR.$module->name.'_'.$module->version.'/' );
        }
    }

    public function importmodule( $get, $post )
    {
        // Читаем свединия о модуле
        $module = $this->db->select( 'SELECT title, descripion, name, version, tables, exist FROM core_modules_group WHERE id='.$get->id->int() )->current();
        
        // Добавляем файлы в архив
        $zip = new ZipArchive;
        if ( $zip->open( TMP_DIR.$module->name.'_'.$module->version.'.zip', ZipArchive::CREATE ) === true )
        {
            $this->addFolderToZip( MODULES_DIR.$module->name.'_'.$module->version.'/', '', $zip );
            
            // Добавляем файл install.txt и db.sql
            $zip->addFromString( '/install.txt', $module );
            $zip->addFromString( '/db.sql', $this->importTable( $module->tables ) );
            $zip->close();
        
            echo 'Импорт модуль завершон. ('.TMP_DIR.$module->name.'_'.$module->version.'.zip)';
        }
        else
        {
            echo 'Error';
        }
    }


    private function addFolderToZip( $src_dir, $desc_dir, $zip )
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
    
    private function importTable( $name )
    { 
        $tables = explode( ';', $name );
        $content = '';
        
        foreach ( $tables as $name )
        {
            $result = $this->db->select( 'SHOW CREATE TABLE `'.$name.'`;' )->current('Create Table');
            //$content .= 'DROP TABLE IF EXISTS `'.$name."`;\n\n";//CREATE TABLE IF NOT EXISTS
            //$content .= $result.";\n\n";
            $content .= preg_replace( '/^CREATE TABLE/ui', 'CREATE TABLE IF NOT EXISTS', $result ).";\n\n";
        }
        
        return $content;
    }

    private function listTable()
    {
        return $this->db->select( 'SHOW TABLES;' );
    }

    public function import( $get, $post )
    {
        // http://dev1.cathod.ru/ajax/admin/admin?action=import

        // Имя скачиваемого файла
        $file = TMP_DIR.'cathod.zip';
            
        // Добавляем файлы в архив
        $zip = new ZipArchive;
        if ( $zip->open( $file, ZipArchive::CREATE ) === true )
        {
            $this->addFolderToZip( APPLICATION_CORE.'/', APPLICATION_CORE.'/', $zip );
            $this->addFolderToZip( MODULES_DIR.'/', MODULES_DIR.'/', $zip );
            $this->addFolderToZip( TEMP_DIR.'/', TEMP_DIR.'/', $zip );
            $this->addFolderToZip( 'media/', 'media/', $zip );
            
            $zip->addFile( '.htaccess' );
            $zip->addFile( 'config.php' );
            $zip->addFile( 'index.php' );
            $zip->addFile( 'robots.txt' );
            
            $zip->addFile( 'import_base.sql' );
            
            
            /*$tables = $this->listTable();
            $t = '';
            foreach ( $tables as $name )
            {
                $t .= $name['Tables_in_'.DB_NAME].';';
            }
            
            $zip->addFromString( '/db.sql', $this->importTable( trim( $t, ';' ) ) );*/
            
            
            $zip->close();
        
            //echo 'Импорт завершон. ('.$file.')';
 
            // Контент-тип означающий скачивание
            header("Content-Type: application/octet-stream");

            // Размер в байтах
            header("Accept-Ranges: bytes");

            // Размер файла
            header("Content-Length: ".filesize($file));

            // Расположение скачиваемого файла
            header("Content-Disposition: attachment; filename=".$file);  

            // Прочитать файл
            readfile( $file );
        }
        else
        {
            echo 'Error';
        }
    }

    private function removeDirectory( $dir )
    {
        if ( ($objs = glob( $dir."/*" )) )
        {
            foreach ( $objs as $obj )
            {
                is_dir( $obj ) ? $this->removeDirectory( $obj ) : unlink( $obj );
            }
        }

        rmdir($dir);
    }
}

?>