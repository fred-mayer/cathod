<?php

include_once( 'parser.php' );

class TParser_lamoda extends TParser_catalog
{
    protected function foreach_page( $url, $cat, $mag )
    {
        $dom = parent::foreach_page( $url, $cat, $mag );

//var_dump($count_p);
//exit();

        $count_p = 2;
        while ( $this->getElement( $dom, 'div', 'content_box' ) !== null )
        {
            parent::foreach_page( $url.'#p='.$count_p, $cat, $mag );
            $count_p++;
        }
//var_dump($count_p);
exit();
    }

    protected function foreach_item( $dom, $cat, $mag )
    {
        if ( ($node = $this->getElement( $dom, 'div', 'content_box' )) !== null )
        {
            if ( ($lis = $node->getElementsByTagName( 'li' )) !== null )
            {
                foreach ( $lis as $li )
                {
                    if ( ($attr = $li->attributes->getNamedItem( 'class' )) !== null )
                    {
                        if ( strpos( $attr->value, 'item') !== false )
                        {
                            $this->item( $li, $cat, $mag );
                        }
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
        return $this->getAttributValue( $node, 'img', 'src' );
    }

    protected function name( $node )
    {
        return trim( $this->getElementValue( $node, 'span', 'product-name' ) ).' '.trim( $this->getElementValue( $node, 'span', 'grid-brand-name' ) );
    }

    protected function price( $node )
    {
        $price = $this->getElementValue( $node, 'span', 'format-price old-price' );
        return preg_replace( '([^0-9])', '', $price == null ? $this->getElementValue( $node, 'span', 'format-price' ) : $price );
    }

    protected function price_old( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'format-price special-price' ) );
    }

    protected function sale( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'label-discount label_quick' ) );
    }

    protected function size( $node )
    {
        if ( ($lis = $node->getElementsByTagName( 'li' )) !== null )
        {
            foreach ( $lis as $li )
            {
                if ( ($attr = $li->attributes->getNamedItem( 'data-logger-click' )) !== null )
                {
                    if ( $attr->value == 'choose_size' )
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

        if ( ($div = $node->getElementById( 'productImagesWrapper' )) !== null )
        {
            $lis = $div->getElementsByTagName( 'img' );
            foreach ( $lis as $li )
            {
                if ( ($attr = $li->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == 'product-big-image' )
                    {
                        if ( ($attr = $li->attributes->getNamedItem( 'data-src' )) !== null )
                        {
                            $array[] = $attr->value;
                        }
                    }
                }
            }
        }
        
        return $array;
    }

    protected function description( $node )
    {
        return trim( $this->getElementValue( $node, 'p', 'description_product' ) );
    }

    protected function articul( $node )
    {
        if ( ($divs = $node->getElementsByTagName( 'td' )) !== null )
        {
            foreach ( $divs as $div )
            {
                if ( ($attr = $div->attributes->getNamedItem( 'itemprop' )) !== null )
                {
                    if ( $attr->value == 'sku' )
                    {
                        return trim( $div->nodeValue );
                    }
                }
            }
        }
        return '';
    }
}

?>