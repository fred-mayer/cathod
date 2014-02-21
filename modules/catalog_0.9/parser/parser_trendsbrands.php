<?php

include_once( 'parser.php' );

class TParser_trendsbrands extends TParser_catalog
{
    protected function foreach_item( $dom, $cat )
    {
        if ( ($node = $this->getElement( $dom, 'ul', 'catalog' )) !== null )
        {
            $lis = $node->getElementsByTagName( 'li' );
            foreach ( $lis as $li )
            {
                $this->item( $li, $cat );
            }
        }
    }

    protected function url( $node, $url='' )
    {
        return $url.preg_replace( '/^(\/)/', '', $this->getAttributValue( $node, 'a', 'href' ) );
    }

    protected function picture( $node, $url='' )
    {
        return $this->getAttributValue( $node, 'img', 'src' );
    }

    protected function name( $node )
    {
        $div = $this->getElement( $node, 'div', 'img' );
        return strip_tags(trim( $div->getElementsByTagName( 'a' )->item(0)->nodeValue ));
    }

    protected function price( $node )
    {
        $price = $this->getElement( $node, 'div', 'price' );
        return preg_replace( '([^0-9])', '', $this->getElementValue( $price, 'strong' ) );
    }

    protected function price_old( $node )
    {
        $price = $this->getElement( $node, 'div', 'price' );
        return preg_replace( '([^0-9])', '', $this->getElementValue( $price, 's', 'old-price' ) );
    }

    protected function sale( $node )
    {
        return trim( str_replace( '%', '', $this->getElementValue( $node, 'span', 'secret_percent' ) ) );
    }

    protected function size( $node )
    {
        if ( ($select = $node->getElementById( 'size' )) !== null )
        {
            $options = $select->getElementsByTagName( 'option' );
            foreach ( $options as $option )
            {
                $size = explode( "(Российский размер", $option->nodeValue );
                if ( $size[0] != 'Выбрать размер' )
                {
                    $array[] = trim($size[0]);
                }
            }
        }
        elseif ( ($div = $node->getElementById( 'center_top' )) !== null )
        {
            if ( ($js = $this->getElement( $div, 'script' )) !== null )
            {
                preg_match( "/document.write\(Base64.decode\(\'([^\.\,\s]+)\'/", $js->nodeValue, $matches );

                //var_dump( base64_decode($matches[1]) );
                
                $dom = new DOMDocument();
                $source = mb_convert_encoding(base64_decode($matches[1]), 'HTML-ENTITIES', 'utf-8');
                $dom->loadHTML( $source );
                
                if ( ($select = $dom->getElementById( 'size' )) !== null )
                {
                    $options = $select->getElementsByTagName( 'option' );
                    foreach ( $options as $option )
                    {
                        $size = explode( "(Российский размер", $option->nodeValue );
                        if ( $size[0] != 'Выбрать размер' )
                        {
                            $array[] = trim($size[0]);
                        }
                    }
                }
            }
        }
        //var_dump($array);

        return new TObject( $array );
    }

    protected function pictures( $node, $url='' )
    {
        if ( ($div = $this->getElement( $node, 'ul', 'catalog_product_previews' )) !== null )
        {
            $lis = $div->getElementsByTagName( 'a' );
            foreach ( $lis as $li )
            {
                if ( ($attr = $li->attributes->getNamedItem( 'largeimg' )) !== null )
                {
                    $array[] = $attr->value;
                }
            }
        }
        elseif ( ($div = $node->getElementById( 'content_top' )) !== null )
        {
            if ( ($js = $this->getElement( $div, 'script' )) !== null )
            {
                preg_match( "/document.write\(Base64.decode\(\'([^\.\,\s]+)\'/", $js->nodeValue, $matches );

                //var_dump( base64_decode($matches[1]) );
                
                $dom = new DOMDocument();
                $source = mb_convert_encoding(base64_decode($matches[1]), 'HTML-ENTITIES', 'utf-8');
                $dom->loadHTML( $source );
                
                if ( ($div = $this->getElement( $dom, 'ul', 'catalog_product_previews' )) !== null )
                {
                    $lis = $div->getElementsByTagName( 'a' );
                    foreach ( $lis as $li )
                    {
                        if ( ($attr = $li->attributes->getNamedItem( 'largeimg' )) !== null )
                        {
                            $array[] = $attr->value;
                        }
                    }
                }
            }
        }
        //var_dump($array);
        
        return $array;
    }

    protected function description( $node )
    {
        return trim( $node->getElementById( 'tabs-1' )->nodeValue );
    }

    protected function articul( $node )
    {
        return preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'purpleflash-thing-id' ) );
    }
}

?>