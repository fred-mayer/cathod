<?php

include_once( MODULES_DIR.'/catalog_0.9/parser/parser_atlasformen.php' );

/*
$value = 'Перевоплотитесь в игрока регби, надев это оригинальное поло! Выполненное из 
    высококачественного трикотажа джерси, оно имеет декоратиивные полоски и принт на 
    груди и на рукавах с эмблемой Вашей команды. Состав: 100% хлопок, плотность 200 г/м². 
    Машинная стирка при 30 °С. Глажка с изнаночной стороны для сохранения трафаретного 
    изображения. Артикул: N0022 Цвет: белый.';

preg_match( "/(Артикул:\s+)([^\.\,\s]+)?/", $value, $matches );

var_dump($matches);
exit();*/
/*
class TParser_catalog extends TXML_handler_tag
{
    protected $db;

    public function __construct( $db, $idmag )
    {
        $this->db = $db;

        $mag = $this->db->select( 'SELECT * FROM catalog_magazine WHERE id='.$idmag )->current();

        $cats = $this->db->select( 'SELECT id_cat, url FROM catalog_mag_cats WHERE id_mag='.$mag->id );
	foreach ( $cats as $cat )
        {
            // Получаем контент
            $dom = new DOMDocument();
            $dom->loadHTMLFile( $cat->url );

            $this->foreach_item( $dom, $cat, $mag );
        }
    }
    
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
    
    private function item( $node, $cat, $mag )
    {
        $item['id_cat'] = $cat->id_cat;
        $item['id_mag'] = $mag->id;
        
        $item['url'] = $mag->url.$this->url( $node );
        $item['picture'] = $this->picture( $node );
        $item['name'] = $this->name( $node );
        $item['price'] = $this->price( $node );
        $item['price_old'] = $this->price_old( $node );
        $item['sale'] = $this->sale( $node );
        
        // переходим на страницу подробней (получаем все фото, размер, ...)
        $dom = new DOMDocument();
        $dom->loadHTMLFile( $item['url'] );

        $attr['size'] = $this->size( $dom );

        $item['description'] = $this->description( $dom );
        $item['articul'] = $this->articul( $item['description'] );

        $item['hash'] = md5( $item['id_mag'].$item['url'].$item['name'].$item['articul'] );
        
        $pictures = $this->pictures( $dom );
        
        
        var_dump( $item, $attr, $pictures );

        $this->insert( $item, $attr, $pictures );
    }
    
    private function insert( $item, $attr, $pictures )
    {
        if ( ($id = $this->db->select( 'SELECT id FROM catalog_items WHERE hash=\''.$item['hash'].'\'' )->current('id')) !== false )
        {
            $this->db->update( 'catalog_items', array( 'price' => $item['price'], 
                                                       'price_old' => $item['price_old'], 
                                                       'sale' => $item['sale'], 
                                                       'date' => date('Y-m-d H:i:s') ), 'id='.$id );

            $this->update_attr( $id, $attr );
            
            return $id;
        }
        else
        {
            if ( ($id = $this->db->insert( 'catalog_items', $item )) !== false )
            {
                $this->insert_attr( $id, $attr );
                $this->insert_pictures( $id, $pictures );
            }
            
            return $id;
        }
    }
    
    private function insert_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            $this->db->insert( 'catalog_attr', array('iditem'=>$id, 'field_name'=>$key, 'field_value'=>$value) );
        }
    }
    
    private function insert_pictures( $id, $pictures )
    {
        foreach ( $pictures as $picture )
        {
            $this->db->insert( 'catalog_pictures', array('iditem'=>$id, 'picture'=>$picture) );
        }
    }
    
    private function update_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            $this->db->update( 'catalog_attr', array( 'field_value'=>$value ), 'iditem='.$id.' AND field_name=\''.$key.'\'' );
        }
    }

    protected function url( $node )
    {
        return $node->parentNode->attributes->getNamedItem( 'href' )->value;
    }

    protected function picture( $node )
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

    protected function pictures( $node )
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
        preg_match( "/(Артикул:\s+)([^\.\,\s]+)?/", $node, $matches );
        
        return trim( $matches[2] );
    }
    
    private function getElement( $node, $tagName, $class )
    {
        $elements = $node->getElementsByTagName( $tagName );
        foreach ( $elements as $element )
        {
            if ( ($attr = $element->attributes->getNamedItem( 'class' )) !== null )
            {
                if ( $attr->value == $class )
                {
                    return $element;
                }
            }
        }
    }
    
    private function getElementValue( $node, $tagName, $class )
    {
        return $this->getElement( $node, $tagName, $class )->nodeValue;
    }
}
*/

$afm = new TParser_atlasformen( $template->db, 1 );

exit();


?>
