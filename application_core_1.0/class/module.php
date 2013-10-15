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
    protected $version;
    protected $data;
    protected $params;
    protected $module_template;
    
    public $template=false;
    public $admin=null;
    public $idmodule;
    public $hold=false; //модуль закреплен на всех страницах
    public $set_pos;
    public $level;
    public $hide;
    public $adminToolbar=true;
    public $icon;

    public $db;
    protected $get;
    protected $post;
    protected $route;
    protected $auth;

    function __construct( $template, $module_name='', $version='' )
    {
        $this->template = $template;
        
        $this->name = $module_name;
        $this->version = $version;
        
        $this->db = $this->template->db;
        $this->get = $this->template->get;
        $this->post = $this->template->post;
        $this->route = $this->template->route;
        $this->auth = $this->template->auth;

        
        if ( $this->template->auth->isAdmin && $module_name != '' ) // Если авторизованы то подключаем админ класс
        {
            if ( ($version = $this->getVersion()) != '' )
            {
                $version = '_'.$version;
            }
            else
            {
                $version = '';
            }


            // Автоматом подгружаем все классы дополнительные класс
            if ( file_exists( MODULES_DIR.$module_name.$version.'/class/' ) )
            {
                $files = scandir( MODULES_DIR.$module_name.$version.'/class/' );

                foreach ( $files as $file )
                {
                    if ( is_file( MODULES_DIR.$module_name.$version.'/class/'.$file ) ) 
                            include_once( MODULES_DIR.$module_name.$version.'/class/'.$file );
                }
            }


            if ( file_exists( MODULES_DIR.$module_name.$version.'/admin.php' ) )
            {
                include_once( MODULES_DIR.$module_name.$version.'/admin.php' );

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
    
    public function getParams()
    {
        return $this->params;
    }

    public function getName()
    {
        return $this->name;
    }
    /*
     * возвращает всю информацию о модуле
     */
    public function getModuleInfo()
    {
        return $this->db->select("SELECT * FROM core_modules WHERE id=".$this->idmodule)->current();
    }

    public function getVersion()
    {
        return $this->version;
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
        //смена шаблон согластно параметрам модуля
        if(!$this->module_template && isset($this->params['template'])){ //если шаблон не был задан программой и есть в параметрах
            if($this->params['template']!='default'){
                $this->module_template = $this->params['template'];
            }
        }
        if($this->module_template=='default'){
            $this->module_template=false;
        }
        if ( !$this->module_template )
        {
            $this->module_template = $this->getName();
    	}
        else
        {
            $this->module_template = $this->getName().'_'.$this->module_template;
    	}
        
        
        if ( ($version = $this->getVersion()) != '' )
        {
            $version = '_'.$version;
        }
        else
        {
            $version = '';
        }


        if ( file_exists( TEMP_DIR.$template->getName().'/modules/'.$this->module_template.'.php' ) )
        {
            include( TEMP_DIR.$template->getName().'/modules/'.$this->module_template.'.php' );
        }
        else
            include( MODULES_DIR.$this->getName().$version.'/template/'.$this->module_template.'.php' );
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
                if ( isset($button['more']) )
                {
                    $more = "";
                    foreach ( $button['more'] as $key=>$value )
                    {
                        $more .= '&'.$key.'='.$value;
                    }
                }


                echo '<a class="btn btn-mini" module="'.$this->getName().'" action="'.$button['action'].'" href="#"'.$attr.(!empty($button['title']) ? ' title="'.$button['title'].'"' : '').(isset($more) ? ' more="'.$more.'"' : '').'>
                        <i class="icon-'.$button['icon'].'"></i>'.(!empty($button['text']) ? ' '.$button['text'] : '').'</a>';
            }
        }
        
        return true;
    }
}
?>