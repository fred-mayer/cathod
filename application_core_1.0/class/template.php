<?php

/**
 * Класс для работы с шаблоном страницы
 * 
 * @author mizko
 * @version 1.0
 * @package class
 */
class TTemplate
{
    /**
     * Переменная хранящая информацию о модулях для текущей позиции
     * 
     * @see setPos
     * @access protected
     * @var array
    */
    protected $posModules;
    /**
     * Название шаблона
     * 
     * @see setName
     * @access protected
     * @var string
    */
    protected $name = '';
    /**
     * Название шаблона по умолчанию. Будет использовано если название {@link $name} шаблона не указано то будет взят шаблон по умолчанию
     * 
     * @access protected
     * @var string
    */
    protected $default = 'temp1';


    public $db;
    public $get;
    public $post;
    public $route;
    public $auth;
    /**
     * Id страницы
     * 
     * @access public
     * @var int
    */
    public $idpage;
    /**
     * Предпросмотр страници
     * 
     * @access public
     * @var boolean
    */
    public $isPreview = false;
    public $displayTools = false;


    public function __construct( $template_name='' )
    {
	$this->db = new TMySQL();
	$this->get = new TMethod( $_GET );
	$this->post = new TMethod( $_POST );
	$this->route = new TRoute( $this->get );    
        $this->auth = new TAuth( $this->db );

        $this->setName( $template_name );
        
        
        $this->isPreview = isset( $this->get->preview );
        if($this->auth->isAdmin && !$this->isPreview){
            $this->displayTools = true;
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
    public function getModule( $module_name, $params=null, $idmodule=0 )
    {
        if ( file_exists( MODULES_DIR.$module_name.'/index.php' ) )
        {
            include_once( MODULES_DIR.$module_name.'/index.php' );

            eval( '$modClass = new T'.$module_name.'($this, \''.$module_name.'\');' ); // возвращаем класс модуля


            if ( $params ) $modClass->setParams( $params );
            if ( $idmodule > 0 ) $modClass->idmodule = $idmodule;


            return $modClass;
        }
        else
        {
            return false;
        }
    }
    /**
     * Функция задает заголовок страницы.
     * 
     * @param string $title Заголовок
     * 
     * @return void
     */
    public function setTitle( $title )
    {
        $this->title = $title;
    }
    /**
     * Функция задает ключевые слова страницы.
     * 
     * @param string $meta_keywords Ключевые слова
     * 
     * @return void
     */
    public function setMetaKeywords( $meta_keywords )
    {
        $this->meta_keywords = $meta_keywords;
    }
    /**
     * Функция задает описание страницы.
     * 
     * @param string $meta_descripion Описание
     * 
     * @return void
     */
    public function setMetaDescripion( $meta_descripion )
    {
        $this->meta_descripion = $meta_descripion;
    }
    /**
     * Функция добавляет скрипт на страницу.
     * 
     * @param string(array) $src Имя файл скрипта, также может быть передан массив в котором перечислены файлы. 
     * (При передаче массива все добавленные ранние скрипты будут потеряны)
     * 
     * @return void
     */
    public function setScript( $src )
    {
        if ( is_array($src) )
            $this->script = $src;
        else
            $this->script[] = $src;
    }
    /**
     * Функция добавляет стиль на страницу.
     * 
     * @param string(array) $src Имя файл стиля, также может быть передан массив в котором перечислены файлы. 
     * (При передаче массива все добавленные ранние стили будут потеряны)
     * 
     * @return void
     */
    public function setStyle( $href )
    {
        if ( is_array($href) )
            $this->style = $href;
        else
            $this->style[] = $href;
    }
    /**
     * Функция устанавливает модуль в позицию
     * 
     * @param string $pos Позиция
     * @param TModule $module Модуль
     * 
     * @return void
     */
    public function setPos( $pos, TModule $module )
    {
        $module->set_pos = $pos;
        
        /*ob_start();

        $module->display( $this );

        $html = ob_get_contents();
        ob_end_clean();


        $class = $module->getName().($this->auth->isAdmin && !$this->isPreview ? ' admin-module' : '');
        $attr = ' idpage="'.$this->idpage.'" idmodule="'.$module->idmodule.'" set_pos="'.$module->set_pos.'" level="'.$module->level.'"';


        $this->posModules[$pos][] = '<div class="'.$class.'"'.$attr.'>'.$this->getAdminToolbar( $module, $attr ).$html.'</div>';*/
        $this->posModules[$pos][] = $this->displayModule( $module );
    }
    /**
     * Функция задает имя шаблона, который в дальнейшем будет загружен
     * 
     * @param string $template_name Имя шаблона
     * 
     * @return void
     */
    public function setName( $template_name )
    {
        $this->name = $template_name;
    }

    /**
     * Функция проверяет наличие модуля в позиции
     * 
     * @param string $pos Позиция
     * 
     * @return boolean, фозвращает true если есть позиции
     */
    public function issetPos( $pos )
    {
        if($this->displayTools){
            return true;
        }else{
            return isset($this->posModules[$pos]);
        }
    }

    /**
     * Функция выводит заголовок страницы.
     * 
     * @return void
     */
    public function getHeader()
    {
        echo '<title>'.$this->title.'</title>';
        
        if ( isset($this->meta_descripion) )
        {
            echo '<meta name="description" content="'.$this->meta_descripion.'">';
        }
        
        if ( isset($this->meta_keywords) )
        {
            echo '<meta name="keywords" content="'.$this->meta_keywords.'">';
        }
        
        echo '<script src="/templates/'.$this->getName().'/js/dialog.js"></script>';
        
        if ( isset($this->script) )
        {
            foreach ( $this->script as $src )
            {
                if ( trim($src) !== '' )
                    echo '<script src="/templates/'.$this->getName().'/js/'.trim($src).'"></script>';
            }
        }
        
        if ( $this->auth->isAdmin ) // Если авторизованы то подключаем админ скрипт
        {
            echo '<script src="/admin/script.js"></script>';
            echo '<link href="/admin/style.css" rel="stylesheet">';
        }
        
        if ( isset($this->style) )
        {
            foreach ( $this->style as $href )
            {
                if ( trim($href) !== '' )
                    echo '<link href="/templates/'.$this->getName().'/style/'.trim($href).'" rel="stylesheet">';
            }
        }
    }
    /**
     * Функция выводит модуль.
     * 
     * @param string $pos Позиция
     * 
     * @return void
     */
    public function getPos( $pos )
    {
        $this->adminContainerStart();//добавляет контейнер для админа
        if ( isset($this->posModules[$pos]) )
        {
            foreach ( $this->posModules[$pos] as $module )
            {
                echo $module;//$module->display( $this );
            }
        }
        //кнопка добавить
        $this->buttonAddModule($pos);
        $this->adminContainerEnd();
    }
    /*
     * 
     */
    public function buttonAddModule($pos){
        if($this->displayTools){
            ?><a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="<? echo $pos ?>" href="#"><i class="icon-plus"></i> Добавить модуль</a><?
        }
    }
    public function adminContainerStart(){
        if($this->displayTools){
            ?><div class="admin_tools"><?
        }
    }
    public function adminContainerEnd(){
        if($this->displayTools){
            ?></div><?
        }
    }
    /*
     * 
     */
    public function getAdminToolbar( TModule $module, $attr )
    {
        if ( $this->auth->isAdmin && !$this->isPreview )
        {
            ob_start();
?>
        <div class="btn-toolbar">
            <div class="btn-group">
<?php

            if ( $module->getAdminToolbar( $attr ) )
            {

?>
                <a class="btn btn-mini" module="admin" action="delmodule"<?php echo $attr; ?> href="#"><i class="icon-remove"></i></a>
                <a class="btn btn-mini move" module="admin" action="upmodule"<?php echo $attr; ?> href="#"><i class="icon-arrow-up"></i></a>
                <a class="btn btn-mini move" module="admin" action="downmodule"<?php echo $attr; ?> href="#"><i class="icon-arrow-down"></i></a>
<?php

            }

?>
            </div>
        </div>
<?php
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
        }
    }
    /**
     * Функция возвращает название шаблона страници.
     * 
     * @return string, если шаблон не указан то будет возвращен шаблон по умолчанию
     */
    public function getName()
    {
        if ( $this->name == '' )
        {
            return $this->default;
        }
        
        return $this->name;
    }

    /**
     * Функция выводит шаблон.
     * 
     * @param string $template_name Имя шаблона (не обязательно)
     * 
     * @return void
     */
    public function display( $template_name='' )
    {
        if ( $template_name != '' )
        {
            $this->setName( $template_name );
        }

        include_once( TEMP_DIR.$this->getName().'/index.php' );
    }
    
    public function displayModule( TModule $module )
    {
        ob_start();

        $module->display( $this );

        $html = ob_get_contents();
        ob_end_clean();


        $class = $module->getName().($this->auth->isAdmin && !$this->isPreview ? ' admin-module' : '');
        $attr = ' idpage="'.$this->idpage.'" idmodule="'.$module->idmodule.'" set_pos="'.$module->set_pos.'" level="'.$module->level.'"';


        return '<div class="'.$class.'"'.$attr.'>'.$this->getAdminToolbar( $module, $attr ).$html.'</div>';
    }

    /**
     * Переадресовываем страницу.
     * 
     * @param string $name Адрес
     * 
     * @return void
     */
    function location( $name='' )
    {
        header( 'Location: '.SERVER_NAME.'/'.$name );
        exit();
    }

    /**
     * 404 Not Found («страница не найдена»).
     * 
     * @return void
     */
    function _404()
    {
        header( "HTTP/1.1 404 Not Found" );
        
        include_once( TEMP_DIR.$this->getName().'/404.php' );
        
        exit();
    }
    function displaySystemMes($mes){
	    return '<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    '.$mes.'
    </div>';
    }
    function printAdminPanel(){
        if($this->auth->isAdmin){
            ?>
                <div id="admin-panel">
                    <p>Админ панель</p>
                    <div class="btn-toolbar-admin">
                        <div class="btn-group">
                            <a href="?preview" class="btn">Предварительный просмотр</a>
                            <button class="btn" module="admin" action="newpage">Новая страница</button>
                            <button class="btn" module="admin" action="editpage" idpage="<? echo $this->idpage; ?>">Редактировать страницу</button>
                            <button class="btn" module="admin" action="copypage" idpage="<? echo $this->idpage; ?>">Клонировать страницу</button>
                        </div>
                    </div>
                </div>
                <div id="admin-sub-panel">
                    <span id="shText" class="btn btn-danger">Админ</span>
                </div>
            <?
        }
        ?><div class="modal" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;"></div><?
    }
}

?>