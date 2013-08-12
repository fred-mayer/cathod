<?php

class TXML_parser
{
    protected $parser = null;
    protected $current = null;
    protected $array = array();
    
    public $handler; // Внешний обрабочик

    public function __construct()
    {
        $this->parser = xml_parser_create();

        xml_set_object( $this->parser, $this );
        
        // Имена элементов приведенные к одному регистру (верхниму). Включено по умолчанию.
        //xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, 0 ); // Данная строка выключает
        
        xml_set_element_handler( $this->parser, 'tag_open', 'tag_close' );
        xml_set_character_data_handler( $this->parser, 'cdata' );
    }

    public function __destruct()
    {
        if ( $this->parser )
            xml_parser_free( $this->parser );
    }

    public function parse( $data )
    {
        xml_parse( $this->parser, $data );
    }
    
    public function parseFile( $file )
    {
        if ( ($f = fopen( $file, 'r' )) === false ) return;

        while ( !feof( $f ) )
        {
            $this->parse( fgets( $f, 8192 ) );
        }

        fclose( $f );
    }

    protected function tag_open( $parser, $tag, $attributes )
    {
        $this->current = $this->array[] = array( 'tag'=>$tag, 'attr'=>$attributes, 'data'=>'' );
    }

    protected function cdata( $parser, $cdata )
    {
        $this->current = array_pop( $this->array );
        $this->current['data'] .= $cdata;
        $this->array[] = $this->current;
    }

    protected function tag_close( $parser, $tag )
    {
        $this->current = array_pop( $this->array );

        $this->handler( $this->current['tag'], $this->current['attr'], $this->current['data'], end( $this->array ) );
    }

    private function handler( $tag, $attr, $data, $parent )
    {
        $this->handlerTag( $tag, $attr, $data, $parent );
        
        // Проверяет, может значение переменной быть вызвано в качестве функции.
        if ( is_callable( $this->handler ) )
        {
            call_user_func_array( $this->handler, func_get_args() );
        }
    }
    
    // Эта функция вызывается для каждого прочитаого тега
    protected function handlerTag( $tag, $attr, $data, $parent )
    {
        
    }
}


class TXML_handler_tag extends TXML_parser
{
    public function __call( $name, $arg )
    {
        if ( method_exists( $this, $name) )
        {
            call_user_func_array( array( &$this, $name ), $arg );
        }
    }

    protected function handlerTag( $tag, $attr, $data, $parent )
    {
        $this->__call( $tag, array( $attr, $data, $parent ) );
    }
}

?>
