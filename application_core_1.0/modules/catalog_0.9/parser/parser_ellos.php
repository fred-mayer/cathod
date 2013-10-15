<?php

include_once( 'parser.php' );

class TParser_ellos extends TParser_catalog
{
    protected function foreach_item( $dom, $cat, $mag )
    {
        $table = $dom->getElementById( 'ctl00_cphMain_ctl00_dlProducts' );
        $tds = $table->getElementsByTagName("td");
        foreach ( $tds as $td )
        {
            if ( ($attr = $td->attributes->getNamedItem( 'class' )) !== null )
            {
                if ( $attr->value == 'showProdV-tdProduct' )
                {
                    $this->item( $td, $cat, $mag );
                }
            }
        }
    }

    protected function url( $node, $url='' )
    {
        $diva = $this->getElement( $node, 'div', 'showProdV-divProdImage' );
        $a = $this->getElement( $diva, 'a' );
        return $url.DS.preg_replace( '/^(\/)/', '', $a->attributes->getNamedItem( 'href' )->value );
    }

    protected function picture( $node, $url='' )
    {
        $diva = $this->getElement( $node, 'div', 'showProdV-divProdImage' );
        $img = $this->getElement( $diva, 'img' );
        
        return $img->attributes->getNamedItem( 'src' )->value;
    }

    protected function name( $node )
    {
        $div = $this->getElement( $node, 'div', 'showProdV-divTitlePrice' );
        return $this->getElementValue( $div, 'a');
    }

    protected function price( $node )
    {
        return mb_substr(preg_replace( '([^0-9])', '', $this->getElementValue( $node, 'span', 'showProdV-spanPrice' )),0,-2 );
    }

    protected function price_old( $node )
    {
        return null;
    }

    protected function sale( $node )
    {
        if($this->getElement($node,"div","showProdV-divTag9")){
            return "-20";
        }else{
            return null;
        }
    }

    protected function size( $node )
    {
        $select = $this->getElement($node,"select", 'selectSize' );
            $options = $select->getElementsByTagName( 'option' );
            foreach ( $options as $option )
            {
                $array[] = $option->nodeValue;
            }
        return new TObject( $array );
    }

    protected function pictures( $node, $url='' )
    {
        
        if ( ($left = $this->getElement( $node, 'div', 'singleProdV-tdProdLeft' )) !== null )
        {
            $divs = $left->getElementsByTagName( 'td' );
            foreach($divs as $d){
                if(($attr=$d->attributes->getNamedItem( 'class' ))== "singleProdV-tdSmallPhoto"){
                    $img = $d->getElementsByTagName( 'img' );
                    $click = $img->attributes->getNamedItem( 'onklick' );
                    $this->log->printr($click);
                    $click = explode(",",$click);
                    $click = str_replace("'","",trim($click[1]));
                    $array[] = $click;
                }
            }
        }
        
        return $array;
    }

    protected function description( $node )
    {
        return trim( $this->getElementValue( $node, 'span', 'description' ) );
    }

    protected function articul( $node )
    {  
        return null;
    }
    protected function articulByUrl($url)
    {
        $urlp = parse_url($url);
        foreach (explode('&', $urlp->query) as $chunk) {
            $param = explode("=", $chunk);

            if ($param[0]=="productID") {
                return $param[1];
            }
        }
    }
}

?>