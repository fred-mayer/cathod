<?php

include_once( 'parser.php' );

class TParser_sapato extends TParser_catalog
{
    protected function foreach_page( $url, $cat, $mag )
    {
        $dom = parent::foreach_page( $url, $cat, $mag );
        
        if ( ($div = $this->getElement( $dom, 'div', 'page-nav' )) !== null )
        {
            $lis = $div->parentNode->getElementsByTagName( 'a' );
            
            $length = $lis->length - 2;
            
            $count_p = preg_replace( '([^0-9])', '', $lis->item( $length )->nodeValue );
        }
//var_dump($count_p);
//exit();
        
        for ( $p = 2; $p < $count_p; $p++ )
        {
            parent::foreach_page( $url.'?page='.$p, $cat, $mag );
        }
    }

    protected function foreach_item( $dom, $cat, $mag )
    {
        if ( ($divs = $dom->getElementsByTagName( 'div' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( strpos( $attr->value, 'catalog-item') !== false )
                    {
                        $this->item( $div, $cat, $mag );
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
        return preg_replace( '/^(\/)/', '', $this->getAttributValue( $node, 'img', 'data-href' ) );
    }

    protected function name( $node )
    {
        return trim( $this->getElementValue( $node, 'span', 'catalog-name' ) );
    }

    protected function price( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'div', 'currency-replace' ) );
    }

    protected function price_old( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'div', 'old' ) );
    }

    protected function sale( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'icon_percentage' ) );
    }

    protected function size( $node )
    {
        if ( ($lis = $node->getElementsByTagName( 'li' )) !== null )
        {
            foreach ( $lis as $li )
            {
                if ( ($attr = $li->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == 'available' )
                    {
                        $array[] = $li->nodeValue;
                    }
                }
            }
        }

        return new TObject( $array );
    }

    protected function pictures( $node, $url='' )
    {
        $array = array();

        if ( ($divs = $node->getElementsByTagName( 'ul' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == 'img-small clearfix' )
                    {
                        $lis = $div->getElementsByTagName( 'a' );
                        foreach ( $lis as $li )
                        {
                            if ( ($attr = $li->attributes->getNamedItem( 'class' )) !== null )
                            {
                                if ( $attr->value == 'cloud-zoom-gallery-new' )
                                {
                                    if ( ($attr = $li->attributes->getNamedItem( 'href' )) !== null )
                                    {
                                        $array[] = preg_replace( '/^(\/)/', '', $attr->value );
                                    }
                                }
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
        if ( ($divs = $node->getElementsByTagName( 'div' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'itemprop' )) !== null )
                {
                    if ( $attr->value == 'description' )
                    {
                        if ( ($ps = $div->getElementsByTagName( 'p' )) !== null )
                        {
                            return ($ps->item( 1 )->nodeValue === '') ? $ps->item( 0 )->nodeValue : $ps->item( 1 )->nodeValue;
                        }
                    }
                }
            }
        }
        return '';
    }

    protected function articul( $node )
    {
        if ( ($div = $node->getElementById( 'breadcrumbs' )) !== null )
        {
            return $this->getElementValue( $div, 'strong' );
        }
        return '';
    }
}

?>