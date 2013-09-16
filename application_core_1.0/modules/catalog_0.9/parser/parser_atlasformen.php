<?php

include_once( 'parser.php' );

class TParser_atlasformen extends TParser_catalog
{
    protected function foreach_item( $dom, $cat, $mag )
    {
        $divs = $dom->getElementsByTagName( 'div' );
        foreach ( $divs as $div )
        {
            if ( ($attr = $div->attributes->getNamedItem( 'class' )) !== null )
            {
                if ( $attr->value == 'divProduct' )
                {
                    $this->item( $div, $cat, $mag );
                }
            }
        }
    }

    protected function url( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', $node->parentNode->attributes->getNamedItem( 'href' )->value );
    }

    protected function picture( $node, $url='' )
    {
        $style = $this->getElement( $node, 'div', 'divTagging' )->attributes->getNamedItem( 'style' )->value;
        return str_replace( "background-image: url('", '', str_replace( "');", '', $style ) );
    }

    protected function name( $node )
    {
        return $node->getElementsByTagName( 'h1' )->item(0)->nodeValue;
    }

    protected function price( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'spanUnit' ) );
    }

    protected function price_old( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'spanOldPrice' ) );
    }

    protected function sale( $node )
    {
        return str_replace( '%', '', $this->getElementValue( $node, 'span', 'spanValue' ) );
    }

    protected function size( $node )
    {
        if ( ($select = $node->getElementById( 'cmbSizes' )) !== null )
        {
            $options = $select->getElementsByTagName( 'option' );
            foreach ( $options as $option )
            {
                if ( ($attr = $option->attributes->getNamedItem( 'value' )) !== null )
                {
                    if ( $attr->value != '-1' )
                        $array[] = $attr->value;
                }
            }
        }

        return new TObject( $array );
    }

    protected function pictures( $node, $url='' )
    {
        if ( ($div = $this->getElement( $node, 'div', 'divPDLeft' )) !== null )
        {
            $lis = $div->getElementsByTagName( 'li' );
            foreach ( $lis as $li )
            {
                if ( ($attr = $li->attributes->getNamedItem( 'onclick' )) !== null )
                {
                    preg_match( "/(http:\/\/)([^\']+)/", $attr->value, $matches );

                    $array[] = $matches[0];
                }
            }
        }
        
        return $array;
    }

    protected function description( $node )
    {
        return trim( $this->getElementValue( $node, 'p', 'pProdDescription' ) );
    }

    protected function articul( $node )
    {
        $desc = $this->description( $node );
        preg_match( "/(Артикул:\s+)([^\.\,\s]+)?/", $desc, $matches );
        
        return trim( $matches[2] );
    }
}

?>