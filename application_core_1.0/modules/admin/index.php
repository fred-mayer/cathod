<?php

class Tadmin extends TModule
{
    // Функция возвращает все что знаем о странице
    public function getPage( $id )
    {
        return $this->template->db->select( 'SELECT * FROM core_page WHERE id='.$id )->current();
    }
    
    // Функция возвращает список шаблонов
    public function getTemplats()
    {
        return $this->template->db->select( 'SELECT name AS value, title FROM core_templates' )->toObject();
    }
    
    public function getPages($exclude = null)
    {
	    return $this->template->db->select("SELECT id as value, alias as title FROM core_page WHERE id_parent=0".(($exclude!==null)? "AND id!=".$exclude:""))->toObject();;
    }
    
    public function getModules( $name='' )
    {
        if ( $name == '' )
            return $this->template->db->select( 'SELECT * FROM core_modules_group ORDER BY title' );
        else
            return $this->template->db->select( 'SELECT * FROM core_modules WHERE exist=1 AND name=\''.$name.'\' ORDER BY title' );
    }

    public function display( TTemplate $template )
    {

        parent::display( $template );
    }

    public function getAdminToolbar( $attr, $buttons=null )
    {
        return false;
    }
}

?>