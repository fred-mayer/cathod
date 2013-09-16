<?php

include_once( 'parser.php' );

class TParser_4youonly extends TParser_catalog
{
    protected function foreach_page( $url, $cat, $mag )
    {
        $dom = parent::foreach_page( $url, $cat, $mag );
        
        if ( ($div = $this->getElement( $dom, 'ol', 'pagination' )) !== null )
        {
            $lis = $div->getElementsByTagName( 'a' );
            foreach ( $lis as $li )
            {
                $array[] = preg_replace( '([^0-9])', '', $li->nodeValue );
            }
        }
        
        $count_p = $array[ count($array) - 2 ];
        
        for ( $p = 2; $p < $count_p; $p++ )
        {
            parent::foreach_page( $url.'?PAGEN_2='.$p, $cat, $mag );
        }
    }

    protected function foreach_item( $dom, $cat, $mag )
    {
        if ( ($node = $dom->getElementById( 'cataloglist' )) !== null )
        {
            $lis = $node->getElementsByTagName( 'li' );
            foreach ( $lis as $li )
            {
                $this->item( $li, $cat, $mag );
            }
        }
    }

    protected function url( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', $this->getAttributValue( $node, 'a', 'href' ) );
    }

    protected function picture( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', $this->getAttributValue( $node, 'img', 'src' ) );
    }

    protected function name( $node )
    {
        return trim( $this->getElementValue( $node, 'div', 'info' ) );
    }

    protected function price( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'ins' ) );
    }

    protected function price_old( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'del' ) );
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

        if ( ($div = $node->getElementById( 'photos' )) !== null )
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
        return $array;
    }

    protected function description( $node )
    {
        return '';
    }

    protected function articul( $node )
    {
        if ( ($div = $node->getElementById( 'parameters' )) !== null )
        {
            return preg_replace( '([^0-9])', '', $this->getElementValue( $div, 'p', 'weak' ) );
        }
        return '';
    }
}

?>