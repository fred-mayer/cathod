<?php

include_once( 'parser.php' );

class TParser_bag4u extends TParser_catalog
{
    protected function foreach_page( $url, $cat )
    {
        $dom = parent::foreach_page( $url, $cat );
        
        if ( ($div = $this->getElement( $dom, 'td', 'crug_page' )) !== null )
        {
            $lis = $div->parentNode->getElementsByTagName( 'td' );
            
            $length = $lis->length - 2;
            
            $count_p = preg_replace( '([^0-9])', '', $lis->item( $length )->nodeValue );
        }
//var_dump($count_p);
//exit();
        
        for ( $p = 2; $p < $count_p; $p++ )
        {
            parent::foreach_page( $url.'?page='.$p, $cat );
        }
    }

    protected function foreach_item( $dom, $cat )
    {
                        
        if ( ($divs = $dom->getElementsByTagName( 'table' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'width' )) !== null )
                {
                    if ( $attr->value == '200px' )
                    {
                        $this->item( $div, $cat );
                    }
                }
            }
        }
    }

    protected function url( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', trim( $this->getAttributValue( $node, 'a', 'href' ) ) );
    }

    protected function picture( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', $this->getAttributValue( $node, 'img', 'src' ) );
    }

    protected function name( $node )
    {
        if ( ($divs = $node->getElementsByTagName( 'td' )) !== null )
        {
            return trim( $divs->item( 2 )->nodeValue.' '.$divs->item( 3 )->nodeValue );
        }
    }

    protected function price( $node )
    {
        if ( ($divs = $node->getElementsByTagName( 'td' )) !== null )
        {
            $length = $divs->length - 1;
            
            return preg_replace( '([^0-9])', '', $divs->item( $length )->nodeValue );
        }
    }

    protected function price_old( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 's' ) );
    }

    protected function sale( $node )
    {
        return '';
    }

    protected function size( $node )
    {
        return null;
    }

    protected function pictures( $node, $url='' )
    {
        $array = array();

        if ( ($divs = $node->getElementsByTagName( 'td' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == 'tov-pic1' )
                    {
                        $lis = $div->getElementsByTagName( 'a' );
                        foreach ( $lis as $li )
                        {
                            if ( ($attr = $li->attributes->getNamedItem( 'href' )) !== null )
                            {
                                $array[] = $url.preg_replace( '/^(\/)/', '', $attr->value );
                            }
                        }
                    }
                }
            }
        }
        
        return $array;
    }

    protected function description( $node )
    {
        if ( ($div = $node->getElementById( 'tab1' )) !== null )
        {
            return $div->nodeValue;
        }
        return '';
    }

    protected function articul( $node )
    {
        return '';
    }
}

?>