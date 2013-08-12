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
}

?>
