<?php

/**
 * Класс для работы с страницей
 * 
 * @author mizko,mayer
 * @version 1.0
 * @package class
 */
class TPages
{
	static $script;
    protected $db;
    protected $alias;
    public $moduleControl=false; //управляет ли субстраницами модуль

    function __construct( $alias="" )
    {
        $this->alias = $alias;
    }
    
    public function ajax( TTemplate $template )
    {
        $this->db = $template->db;


        if ( ($module = $template->getModule( $this->alias )) !== false ) // Загружаем модуль
        {
            if ( isset($template->get->idpage) )
            {
                $page = $this->getPageById( $template->get->idpage->int() ); // Получаем из базы все что знаем о странице
                $template->setName( $page->template );
            }


            $dialog = new TDialog;


            if ( !$dialog->loadCurrentDialog( DIALOG_DIR, $template, $module ) )
            {
                if ( !$dialog->loadCurrentDialogByModule( MODULES_DIR.$module->getName().'/dialog/', $template, $module ) )
                {
                    if ( !$module->action() ) // Диолог не был загружен то попробуем выполнить действие
                    {
                        echo 'error get';
                    }
                }
            }
        }
        else
        {
            $template->_404();
        }
    }
    
    public function admin( TTemplate $template )
    {
        if ( $template->auth->isAdmin ) // Если авторизованы как админ
        {
            $this->db = $template->db;
            if(isset($template->get->idmodule) && $template->get->dialog!='newmodule'){ //если операции связаны с существующими пользовательскими модулями
                $module_data = $this->getModule($template->get->idmodule->int());
                $module = $template->getModule( $this->alias,$module_data->params,$module_data->id );
            }else{
                $module = $template->getModule( $this->alias );
            }
            
            
            if ( ($module) !== false ) // Загружаем модуль
            {
                if ( isset($template->get->idpage) )
                {
                    $page = $this->getPageById( $template->get->idpage->int() ); // Получаем из базы все что знаем о странице
                    $template->setName( $page->template );
                }


                $dialog = new TDialog;


                if ( !$dialog->loadCurrentDialog( ADMIN_DIR.'dialog/', $template, $module ) )
                {
                    if ( ($version = $module->getVersion()) != '' )
                    {
                        $version = '_'.$version;
                    }
                    else
                    {
                        $version = '';
                    }


                    if ( !$dialog->loadCurrentDialogByModule( MODULES_DIR.$module->getName().$version.'/dialog/admin/', $template, $module ) )
                    {
                        if ( !$module->admin->action() ) // Диолог не был загружен то попробуем выполнить действие
                        {
                            echo 'error get';
                        }
                    }
                }
            }
        }
        else
        {
            $this->ajax( $template );
        }
    }
    /**
     * Функция выводит страницу.
     * 
     * @param TTemplate $template Класс шаблон
     * 
     * @return void
     */
    public function display( TTemplate $template )
    {
        $this->db = $template->db;

        if ( file_exists( PAGES_DIR.$this->alias.'.php' ) )
        {
            include_once( PAGES_DIR.$this->alias.'.php' );
        }
        else
        {
            $page = $this->getPage( $template->route ); // Получаем из базы все что знаем о странице
            $isModuleAlias = $this->isModuleAlias($this->alias);
            if ( empty($page) )
            {
            	
                //Проверяем есть ли модуль с таким алиасом
                if($isModuleAlias===false){
                	$template->_404(); // 404
                }else{ //иначе если модуль есть загружаем страницу и передаем управление модулю
	                $page = $this->getPageByAlias($this->alias);
	                $this->moduleControl = true; //управление субстраницами модулем
                }
            }
            
            // Если не админ и страница скрыта то 404 ошибка
            if ( !$template->auth->isAdmin && $page->hide == 'hide' )
            {
                $template->_404(); // 404
            }

            // Заполняем шаблон
            $template->idpage = $page->id;
            $template->setTitle( $page->title );
            $template->setMetaKeywords( $page->keywords );
            $template->setMetaDescripion( $page->descripion );
            $template->setScript( explode( ',', $page->script ) );
            $template->setStyle( explode( ',', $page->style ) );
            $template->setName( $page->template );
            
            
            //проверяем соответствует ли алиас модулю
                        
            if($isModuleAlias!==false){          
                //$mainModule = $this->getModule($isModuleAlias->id);
                if($isModuleAlias->exist==1){
	                $m_obj = $template->getModule( $isModuleAlias->name, ($isModuleAlias->params == '') ? '' : json_decode( $isModuleAlias->params, true ) ); // Загружаем модуль и задаем ему позицию
	                $m_obj->idmodule = $isModuleAlias->id;
	                $m_obj->hide = 'show';
	                
	                $template->setPos( 'section', $m_obj );
                }
            }
            
            /*// Получаем все данные о стандартных модулях для страницы default *** костыль!!!
            if($this->alias != 'default'){
                $modules = $this->getPageModules( 6 );
                foreach ( $modules as $m )
                {
                    $m_obj = $template->getModule( $m->name, ($m->params == '') ? '' : json_decode( $m->params, true ) ); // Загружаем модуль и задаем ему позицию
                    $m_obj->idmodule = $m->id;
                    $m_obj->hide = $m->hide;

                    if($isModuleAlias===false || ($isModuleAlias!==false && $m->set_pos!='section')){ //Если главный модуль тогда только он в section присутствует!
                        $template->setPos( $m->set_pos, $m_obj );
                    }
                }
            }*/
            
            // Получаем все данные о подключенных модулях для этой странице
            $modules = $this->getPageModules( $page->id );
            foreach ( $modules as $m )
            {
                if ( ($m->idpage == 0 ? $this->getHide_Modules( $page->id, $m->id, $m->set_pos ) : $m->hide) !== 'hide' )
                {
                    $m_obj = $template->getModule( $m->name, ($m->params == '') ? '' : json_decode( $m->params, true ) ); // Загружаем модуль и задаем ему позицию
                    $m_obj->idmodule = $m->id;
                    $m_obj->set_pos = $m->set_pos;
                    $m_obj->level = $m->level;
                    $m_obj->hide = $m->idpage == 0 ? $this->getHide_Modules( $page->id, $m->id, $m->set_pos ) : $m->hide;
                    //модуль закреплен на всех страницах?
                    $holdModules = $this->getPageModules( 0 );
                    foreach ( $holdModules as $hm ){
                        if($hm->id==$m->id){
                            $m_obj->hold=true;
                        }
                    }
                    
                    // Если главный модуль тогда только он в section присутствует!
                    if ( $isModuleAlias === false || $isModuleAlias->exist=="0" || ($isModuleAlias !== false && $m->set_pos != 'section') ) 
                    { 
                        $template->setPos( $m->set_pos, $m_obj );
                    }
                }
            }
            
            
            // Всегда подключаем админ модуль
            $m_obj = $template->getModule( 'admin' ); // Загружаем модуль и задаем ему позицию
            $m_obj->adminToolbar = false;

            $template->setPos( 'admin-panel', $m_obj );

        }


        $template->display();
    }
    
    protected function isModuleAlias($alias)
    {
        return $this->db->select('SELECT m.*,a.* FROM core_modules_group as m LEFT JOIN core_aliases_modules as a ON m.name=a.module_name WHERE (m.name LIKE \''.$alias.'\' OR a.alias LIKE \''.$alias.'\') LIMIT 1')->current();
    }
    
    protected function getPage( $alias )
    {
        //var_dump($alias);
        //exit();
        if (isset($alias[0]))
        {
	        $pagename = $alias[0];
	        //ищем детей
	        $parent = "";
	        for($i=0;$i<count($alias);$i++)
	        {
		        if(!empty($alias[$i])){
			        $res = $this->db->select( "SELECT * FROM core_page WHERE alias='".$alias[$i]."'". (($i==0)? " AND id_parent=0":"") . ((!empty($parent))? " AND id_parent=".$parent->id:"") )->current();
			        if(isset($res->id)){ //Страница есть
			        	$page = $res;
				        $parent = $res;
		        	}
		        	if(!isset($res->id) && !empty($parent)){
			        	return null;
			        	break;
		        	}
		        }
		        
	        }
	        
        }else{
	        $pagename = 'default';
	        $page = $this->db->select( 'SELECT * FROM core_page WHERE alias=\''.$pagename.'\'' )->current();
        }
        return $page;
    }
    
    protected function getPageById( $idpage )
    {
        return $this->db->select( 'SELECT * FROM core_page WHERE id='.$idpage )->current();
    }
    
    protected function getPageByAlias( $alias )
    {
        return $this->db->select( 'SELECT * FROM core_page WHERE alias=\''.$alias.'\'' )->current();
    }

    protected function getModule( $idmodule )
    {
        return $this->db->select( 'SELECT m.id, m.name, m.params FROM core_modules m WHERE m.id='.$idmodule )->current();
    }

    protected function getPageModules( $idpage )
    {
        return $this->db->select( 'SELECT m.id, p.idpage, p.set_pos, m.name, m.params, p.`hide`, p.level 
                                        FROM core_page_modules p, core_modules m 
                                        WHERE p.idpage='.$idpage.' AND p.idmodule=m.id
                                            ORDER BY p.level' );
    }

    protected function getHide_Modules( $idpage, $idmodule, $set_pos )
    {
        $row = $this->db->select( 'SELECT p.`hide` FROM core_page_modules p WHERE p.idpage='.$idpage.' AND p.idmodule='.$idmodule.' AND p.set_pos=\''.$set_pos.'\'' )->current('hide');
        return $row === false ? 'show' : $row;
    }
}

?>