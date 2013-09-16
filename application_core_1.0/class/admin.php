<?php

class TBAdmin
{
    protected $template;
    protected $parent;
    protected $db;
    
    protected $result;

    public function setParentModule( $module )
    {
        $this->parent = $module;
        $this->template = $this->parent->template;
        $this->db = $this->template->db;
    }

    public function action()
    {
        if ( isset($this->parent->template->get->action) ) // выполняем действие
        {
            eval( '$this->'.$this->parent->template->get->action.'( $this->parent->template->get, $this->parent->template->post );' );

            if ( !empty($this->result) )
            {
                echo json_encode( $this->result );
            }

            return true;
        }
        else
            return false;
    }
    /**
     * Функция записывает новые параметры модуля
     * 
     * @param int $idmodule - id модуля
     * @param array $params - параметры модуля
     */
    protected function saveModuleParams($idmodule,array $params){
        return $this->db->update('core_modules',array("params"=>json_encode($params)),"id=".$idmodule);
    }
    
    /*
     * Записывает имя модуля
     * 
     * @param int $idmodule - id модуля
     * @param string $name - имя модуля 
     */
    protected function changeNameModule($idmodule, $name){
        return $this->db->update('core_modules',array("title"=>$name),"id=".$idmodule);
    }
    
}

?>
