<?php

class TObject implements Iterator, ArrayAccess, Countable
{
    protected $array;

    public function __construct( &$array=null )
    {
        if ( is_array( $array ) )
        {
            $this->array = &$array;
        }
        elseif ( is_string( $array ) )
        {
            $this->decode_json( $array );
        }
    }

    public function current()
    {
        return $this->offsetGet( $this->key() );
    }

    public function next()
    {
        next( $this->array );
    }
    
    public function key()
    {
        return key( $this->array );
    }

    public function valid()
    {
        return $this->offsetExists( $this->key() );
    }

    public function rewind()
    {
        return reset( $this->array );
    }

    public function count()
    {
        return sizeof( $this->array );
    }

    public function offsetExists( $offset )
    {
        return isset( $this->array["$offset"] );
    }

    public function offsetGet( $offset )
    {
        if ( $this->offsetExists( $offset ) )
        {
            return is_array( $this->array["$offset"] ) ? new TObject( $this->array["$offset"] ) : $this->array["$offset"];
        }
        return null;
    }

    public function offsetSet( $offset, $value )
    {
        if ( is_null( $offset ) )
        {
            $this->array[] = $value;
        }
        elseif ( $this->offsetExists( $offset ) && is_array( $this->array["$offset"] ) )
        {
            $this->array["$offset"] += $value;
        }
        else
            $this->array["$offset"] = $value;
    }

    public function offsetUnset( $offset )
    {
        unset( $this->array["$offset"] );
    }

    public function decode_json( $data )
    {
        if ( is_string( $data ) )
        {
            $array = json_decode( $data, true );
            
            if ( !is_null( $array ) ) 
                $this->array = $array;
        }
    }

    public function encode_json()
    {
        return json_encode( $this->array );
    }

    public function __toString()
    {
        return $this->encode_json();
    }

    public function __get( $name )
    {
        return $this->offsetGet( $name );
    }

    public function __set( $name, $value )
    {
        $this->offsetSet( $name, $value );
    }

    public function __isset( $name )
    {
        return $this->offsetExists( $name );
    }

    public function __unset( $name )
    {
        $this->offsetUnset( $name );
    }
    
    public function __invoke( $value )
    {
        if ( is_array( $value ) )
        {
            unset( $this->array );
            
            $this->array = $value;
        }
        elseif ( is_string( $value ) )
        {
            $this->decode_json( $value );
        }
        elseif ( $value instanceof TObject )
        {
            unset( $this->array );
            
            foreach ( $value as $n=>$v )
            {
                $this->array["$n"] = $v;
            }
        }
    }
    
    static public function valueObjectToString( &$object){
        foreach ($object as $o){
            $o = (string) $o;
        }
    }
    
    // Добавляем массив в объект
    final public function merge( $value )
    {
        if ( is_array( $value ) )
        {
            $this->array = array_merge( $this->array, $value );
        }
    }
    
    final public function toArray()
    {
        return $this->array;
    }
}

?>