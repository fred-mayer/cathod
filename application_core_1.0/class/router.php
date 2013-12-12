<?

/**
 * Класс для работы URL
 * 
 * @author mizko
 * @version 1.0
 * @package class
 */
class TRoute implements ArrayAccess, Countable
{
    protected $params;

    function __construct( TMethod $get )
    {
        if ( isset($get->ajax) && $get->ajax != '' ) $this->params = explode( '/', $get->ajax );
        if ( isset($get->admin) && $get->admin != '' ) $this->params = explode( '/', $get->admin );
        if ( isset($get->params) && $get->params != '' ) $this->params = explode( '/', $get->params );
    }

    /**
     * Функция загрузки контроллера-страницы
     * 
     * @return TPages
     */
    public function loadCurrentPage()
    {
        return new TPages( isset($this[0]) ? $this[0] : 'default' );
    }
    /**
     * Функция загрузки модуля
     * 
     * @param TTemplate $template Класс шаблон
     * 
     * @return TModule, в случаи ошибки false
     */
    /*public function loadCurrentModule( TTemplate $template )
    {
        return isset($this[0]) ? $template->getModule( $this[0] ) : false; // загружаем модуль
    }*/

    /**
     * Функция возвращает название текущей страницы
     * 
     * @return string
     */
    public function getCurrentPage()
    {
        return isset($this[0]) ? $this[0] : 'default';
    }

    public function count()
    {
        return sizeof( $this->params );
    }

    public function offsetExists( $offset )
    {
        return isset( $this->params["$offset"] );
    }

    public function offsetGet( $offset )
    {
        if ( $this->offsetExists( $offset ) )
        {
            return TMySQL::real_escape_string( $this->params["$offset"] );
        }
        return null;
    }

    public function offsetSet( $offset, $value )
    {
        $this->params["$offset"] = $value;
    }

    public function offsetUnset( $offset )
    {
        unset( $this->params["$offset"] );
    }
}
?>