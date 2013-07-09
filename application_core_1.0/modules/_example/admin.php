<?php

class Tadmin_example extends TBAdmin
{
    public function edit( $get, $post )
    {
        // Вызываем при редактировании
    }
    
    
    // Обезательно, автоматом вызывается из модуля админ
    public function insert( $post )
    {
        // делаем вставку в базу
        
        return array('id' => $id); // params - массив с параметрами тоже самое что и в - getModule( $module_name, $params='' )
    }
}

?>