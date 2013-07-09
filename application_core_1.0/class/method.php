<?php

include_once( 'object.php' );
include_once( 'string.php' );
include_once( 'mysql.php' );


class TMethod extends TObject
{    
    public function offsetGet( $offset )
    {
        if ($this->offsetExists( $offset ))
        {
            return is_array( $this->array[$offset] ) ? new TMethod( $this->array[$offset] ) : new TString( TMySQL::real_escape_string( $this->array[$offset] ) );
        }
        return null;
    }
}


final class TCookies extends TMethod
{
    protected $time;
    protected $path = '/';
    protected $domain = '';

    public function __construct( $time=2592000 ) // По умолчанию период действия 30 дней
    {
        parent::__construct( $_COOKIE );

        $this->time = $time;
        //$this->domain = isset( $_SERVER["HTTP_HOST"] ) ? $_SERVER["HTTP_HOST"] : '';
    }

    public function offsetSet( $offset, $value )
    {
        if ( $value == '' )
        {
            $this->__unset( $offset );
        }
        elseif ( !is_null( $offset ) )
        {
            parent::offsetSet( $offset, $value );
            setCookie( $offset, $value, time() + $this->time, $this->path, $this->domain );
        }
    }
    
    public function offsetUnset( $offset )
    {
        parent::offsetUnset( $offset );
        setCookie( $offset, '', time() - $this->time, $this->path, $this->domain ); // Удаляем Cookies
    }
}

?>