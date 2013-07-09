<?php

/**
 * Базовый класс модуля
 * 
 * @author mizko
 * @version 1.0
 * @package class
 */
class TModule
{
    protected $name;
    protected $data;
    protected $params;
    protected $module_template;
    
    public $template;
    public $admin=null;
    public $idmodule;
    public $set_pos;
    public $level;
    public $hide;
    
    protected $db;
    protected $get;
    protected $post;
    protected $route;
    protected $auth;

    function __construct( $template, $module_name='' )
    {
        $this->template = $template;
        
        $this->name = $module_name;
        $this->db = $this->template->db;
        $this->get = $this->template->get;
        $this->post = $this->template->post;
        $this->route = $this->template->route;
        $this->auth = $this->template->auth;


        if ( $this->template->auth->isAdmin && $module_name != '' ) // Если авторизованы то подключаем админ класс
        {
            if ( file_exists( MODULES_DIR.$module_name.'/admin.php' ) )
            {
                include_once( MODULES_DIR.$module_name.'/admin.php' );

                $class = 'Tadmin_'.$module_name;
	        $this->admin = new $class;

                $this->admin->setParentModule( $this );
            }
        }
    }

    /**
     * Функция загрузки модуля
     * 
     * @param string $module_name Название модуля
     * @param mixed $params Парамеры которые будут переданы в модуль (не обязательно)
     * @param int $idmodule Id модуля (не обязательно)
     * 
     * @return module, в случаи ошибки false
     */
    public function getModule( $module_name, $params='', $idmodule=0 ) // функция загрузки модуля
    {
        return $this->template->getModule( $module_name, $params, $idmodule );
    }
    
    public function getParams(){
	    return $this->params;
    }

    public function getName()
    {
        return $this->name;
    }
  
    public function getData()
    {
        return $this->data;
    }
    
    public function setParams($params){
	    $this->params = $params;
    }

    public function display( TTemplate $template )
    {
    	if(!$this->module_template){
	    	$this->module_template = $this->getName();
    	}else{
	    	$this->module_template = $this->getName() . "_".$this->module_template;
    	}
        include( TEMP_DIR.$template->getName().'/modules/'.$this->module_template.'.php' );
    }

    public function action()
    {
        if ( isset($this->template->get->action) ) // выполняем действие
        {
            eval( '$this->'.$this->template->get->action.'( $this->template->get, $this->template->post );' );

            return true;
        }
        else
            return false;
    }
    
    public function getAdminToolbar( $attr, $buttons=null )
    {
        if ( $buttons !== null )
        {
            foreach ( $buttons as $button )
            {
                echo '<a class="btn btn-mini" module="'.$this->getName().'" action="'.$button['action'].'" href="#"'.$attr.(isset($button['title']) ? ' title="'.$button['title'].'"' : '').'>
                        <i class="icon-'.$button['icon'].'"></i>'.(isset($button['text']) ? ' '.$button['text'] : '').'</a>';
            }
        }
    }
}
?>