<?php

class TBaseString implements Iterator, Countable, ArrayAccess
{
    protected $encoding;
    protected $position=0;

    public function __construct( $encoding='utf-8' )
    {
        $this->encoding = $encoding;
    }

    public function current()
    {
        return $this->offsetGet( $this->position );
    }

    public function next()
    {
        $this->position++;
    }
    
    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->offsetExists( $this->key() );
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count() {}

    public function offsetExists( $offset ) {}

    public function offsetGet( $offset ) {}

    public function offsetSet( $offset, $value ) {}

    public function offsetUnset( $offset ) {}
}


final class TString extends TBaseString
{
    protected $str;

    public function __construct( $value='', $encoding='UTF-8' )
    {
        parent::__construct( $encoding );
        
        $this->__invoke( $value );
        
        mb_regex_encoding( $this->encoding );
        mb_internal_encoding( $this->encoding );
    }

    public function count()
    {
        return mb_strlen( $this->str, $this->encoding );
    }

    public function offsetExists( $offset )
    {
        return $offset >= 0 && $offset < $this->count();
    }

    public function offsetGet( $offset )
    {
        if ( $this->offsetExists( $offset ) )
        {
            return mb_substr( $this->str, $offset, 1, $this->encoding );
        }
        return null;
    }

    public function offsetSet( $offset, $value )
    {
        if ( is_null( $offset ) )
            $this->str .= mb_substr( $value, 0, 1, $this->encoding );
        else
            $this->str = mb_substr( $this->str, 0, $offset, $this->encoding ).
                         mb_substr( $value, 0, 1, $this->encoding ).
                         mb_substr( $this->str, $offset+1, $this->count(), $this->encoding );
    }

    public function offsetUnset( $offset )
    {
        $this->str = mb_substr( $this->str, 0, $offset, $this->encoding ).mb_substr( $this->str, $offset+1, $this->count(), $this->encoding );
    }
    
    public function __toString()
    {
        return $this->str;
    }
    
    public function __invoke( $value )
    {
        $this->str = strval( $value );
    }
    
    public function toLower()
    {
        return $this->str = mb_strtolower( $this->str, $this->encoding );
    }
    
    public function toUpper()
    {
        return $this->str = mb_strtoupper( $this->str, $this->encoding );
    }
    
    public function toTranslit()
    {
        return new TString( strtr( $this->str, array('А'=>'A','Б'=>'B','В'=>'V','Г'=>'G', 'Д'=>'D','Е'=>'E','Ж'=>'J','З'=>'Z','И'=>'I', 'Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T', 'У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'TS','Ч'=>'CH', 'Ш'=>'SH','Щ'=>'SCH','Ъ'=>'','Ы'=>'YI','Ь'=>'', 'Э'=>'E','Ю'=>'YU','Я'=>'YA','а'=>'a','б'=>'b', 'в'=>'v','г'=>'g','д'=>'d','е'=>'e','ж'=>'j', 'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l', 'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r', 'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h', 'ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'y', 'ы'=>'yi','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya') ) );
    }
    
    public function toURI()
    {
        $replaces = array(" ",",");
        $uri = rawurlencode(str_replace($replaces, "-", $this->toTranslit($this->toLower($this->str))));
        preg_match_all("/[\w\d\s]+/", $uri, $matches);
        
        return new TString(implode("-", $matches));
    }
    
    public function substr( $offset ) // return string
    {
        return mb_substr( $this->str, 0, $offset, $this->encoding );
    }
    
    public function pos( $needle, $offset ) // return int или false
    {
        return mb_strpos( $this->str, $needle, $offset, $this->encoding );
    }
    
    public function match( $pattern, $option='imsr' ) // return bool
    {
        return mb_ereg_match( $pattern, $this->str, $option );
    }
    
    public function replace( $pattern, $replacement='', $option='imsr' )
    {
        return $this->str = mb_ereg_replace( $pattern, $replacement, $this->str, $option );
    }
    
    public function split( $pattern="\W"/*"[\s.,:?!]"*/ ) // return array
    {
        //return new TStringList( preg_split( $pattern, $this->str, -1, PREG_SPLIT_NO_EMPTY ), $this->encoding );
        return mb_split( $pattern, $this->str );
    }

    public function levenshtein( TString $string )
    {
	$count1 = $this->count();
	$count2 = $string->count();

	for ($i = 0; $i <= $count1; $i++) $distance[$i][0] = $i;
	for ($i = 0; $i <= $count2; $i++) $distance[0][$i] = $i;


	for ( $i = 1; $i <= $count1; $i++ )
        {            
            for ( $j = 1; $j <= $count2; $j++ )
            {
                if ( $this[$i-1] === $string[$j-1] )
                    $cost = 0;
                else
                    $cost = 1;
                
                
		$distance[$i][$j] = min( $distance[$i-1][$j] + 1, $distance[$i][$j-1] + 1, $distance[$i-1][$j-1] + $cost );
            }
	}
        
	return $distance[$count1][$count2];
    }

    public function soundex()
    {
        //$soundex_table = array(0, 1, 3, 6, 0, 2, 4, 0, 0, 4, 3, 7, 8, 8, 0, 1, 5, 9, 3, 6, 0, 2, 0, 5, 0, 5, 6, 3, 3, 3, 0, 0, 0);
        $soundex_table = array(0, 1, 2, 3, 0, 1, 2, 0, 0, 2, 2, 4, 5, 5, 0, 1, 2, 6, 2, 3, 0, 1, 0, 2, 0, 2, 3, 2, 2, 2, 0, 0, 0, 0);
        $char_table = array('A'=>0, 'B'=>1, 'C'=>2, 'D'=>3, 'E'=>4, 'F'=>5, 'G'=>6, 'H'=>7, 'I'=>8, 'J'=>9, 'K'=>10, 'L'=>11, 'M'=>12, 'N'=>13, 'O'=>14, 'P'=>15, 'Q'=>16, 'R'=>17, 'S'=>18, 'T'=>19, 'U'=>20, 'V'=>21, 'W'=>22, 'X'=>23, 'Y'=>24, 'Z'=>25,
                            'А'=>0, 'Б'=>1, 'В'=>21, 'Г'=>6, 'Д'=>3, 'Е'=>4, 'Ё'=>4, 'Ж'=>9, 'З'=>25, 'И'=>8, 'Й'=>24, 'К'=>10, 'Л'=>11, 'М'=>12, 'Н'=>13, 'О'=>14, 'П'=>15, 'Р'=>17, 'С'=>18, 'Т'=>19, 'У'=>20, 'Ф'=>5, 'Х'=>7, 'Ц'=>26, 'Ч'=>27, 'Ш'=>28, 'Щ'=>29, 'Ы'=>30, 'Э'=>4, 'Ю'=>31, 'Я'=>32);
  
        $last = -1;
        $count = $this->count();
        $str = mb_strtoupper( $this->str, $this->encoding );
        
        for ( $i = 0, $small = 0; $i < $count && $small < 4; $i++ )
        {
            $char = mb_substr( $str, $i, 1, $this->encoding );

            if ( isset( $char_table[$char] ) )
            {
                $code = $char_table[$char];
                
                if ( $small == 0 )
                {
                    $soundex[$small++] = strtr( $char, array('А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'J', 'З'=>'Z', 'И'=>'I', 'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R', 'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Х'=>'H', 'Ц'=>'T', 'Ч'=>'C', 'Ш'=>'S', 'Щ'=>'S', 'Ы'=>'Y', 'Э'=>'E', 'Ю'=>'Y', 'Я'=>'Y') );
                    $last = $soundex_table[$code];
                }
                else
                {
                    $code = $soundex_table[$code];
                    if ( $code != $last )
                    {
                        if ( $code != 0 )
                        {
                            $soundex[$small++] = $code;
                        }
                        $last = $code;
                    }
                }
            }
        }
        
        while ( $small < 4 )
        {
            $soundex[$small++] = '0';
        }
        
        return implode( '', $soundex );
    }
    
    public function int() // return int
    {
        return intval( $this->str );
    }
    
    public function float() // return float
    {
        return floatval( $this->str );
    }
    public function convertDate (){
        $e = explode(" ", $this->str);
        $e = $e[0];
        $e = explode("-", $e);
        return implode(".", array_reverse($e));
    }
}


final class TStringList extends TBaseString
{
    protected $array;

    public function __construct( $value=null, $encoding='UTF-8' )
    {
        parent::__construct( $encoding );
        
        $this->__invoke( $value );
    }

    public function count()
    {
        return sizeof( $this->array );
    }

    public function offsetExists( $offset )
    {
        return isset( $this->array[$offset] );
    }

    public function offsetGet( $offset )
    {
        if ( $this->offsetExists( $offset ) )
        {
            return $this->array[$offset];
        }
        return null;
    }

    public function offsetSet( $offset, $value )
    {
        if ( is_string( $value ) && $value != '' )
        {
            $word = new TString( $value, $this->encoding );
        }
        elseif ( $value instanceof TString )
        {
            $word = $value;
        }
        else
            return;


        if ( is_null( $offset ) )
            $this->array[] = $word;
        else
            $this->array[$offset] = $word;
    }

    public function offsetUnset( $offset )
    {
        unset( $this->array[$offset] );
    }
    
    public function join( $join=' ')
    {
        $str = $comma = '';
        foreach ( $this as $word )
        {
            $str .= $comma.$word;
            $comma = $join;
        }
        return $str;
    }
    
    public function __toString()
    {
        return $this->join();
    }
    
    public function __invoke( $value )
    {
        if ( is_array( $value ) )
        {
            unset( $this->array );
            
            foreach ( $value as $word )
            {
                if ( is_string( $word ) && $word != '' )
                {
                    $this->array[] = new TString( $word, $this->encoding );
                }
                elseif ( $word instanceof TString )
                {
                    $this->array[] = $word;
                }
            }
        }
        elseif ( is_string( $value ) )
        {
            unset( $this->array );
            
            $string = new TString( $value, $this->encoding );
            $array = $string->split();
            
            foreach ( $array as $word )
            {
                if ( $word != '' )
                    $this->array[] = new TString( $word, $this->encoding );
            }
        }
        elseif ( $value instanceof TString )
        {
            unset( $this->array );
            
            $array = $value->split();
            
            foreach ( $array as $word )
            {
                if ( $word != '' )
                    $this->array[] = new TString( $word, $this->encoding );
            }
        }
    }
}

?>