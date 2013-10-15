<?php

error_reporting( E_ERROR | E_NOTICE );
include_once( 'log.php' );
abstract class TParser_catalog
{
    protected $db;
    protected $log;

    public function __construct( $db, $idmag )
    {
        $this->db = $db;
        $this->log = new log($db);
        $this->log->getStart();
        
        $mag = $this->db->select( 'SELECT * FROM catalog_magazine WHERE id='.$idmag )->current();

        $cats = $this->db->select( 'SELECT id_cat, url FROM catalog_mag_cats WHERE id_mag='.$mag->id );
        
        if($this->log->loadStep($idmag)){
            $curCat = $this->log->loadStep($idmag);
        }
	foreach ( $cats as $cat )
        {
            // Получаем контент
            if (!isset($curCat) || $curCat==$cat->id_cat){
                if(isset($curCat)) unset($curCat);
                
                $expCat = explode(";",$cat->url);
                if(count($expCat)>1){
                    foreach($expCat as $c){
                        $this->foreach_page( $c, $cat, $mag );
                    }
                }else{
                    $this->foreach_page( $cat->url, $cat, $mag );
                }
            }
        }
    }
    
    protected function file_get_contents_curl( $url, $post=null )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        //curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        $data = curl_exec( $ch );
        
        curl_close( $ch );
        
        return $data;
    }
    
    protected function foreach_page( $url, $cat, $mag )
    {
        var_dump( 'item_cat', $url, $cat->name );
        $this->log->saveStep($mag->id,$cat->id_cat);
        
        // Получаем контент
        $dom = new DOMDocument();
        $source = mb_convert_encoding($this->file_get_contents_curl( $url ), 'HTML-ENTITIES', 'utf-8');
        $dom->loadHTML( $source );

        $this->foreach_item( $dom, $cat, $mag );

        return $dom;
    }
    
    abstract protected function foreach_item( $dom, $cat, $mag );
    
    protected function item( $node, $cat, $mag )
    {
        $item['id_cat'] = $cat->id_cat;
        $item['id_mag'] = $mag->id;
        
        $item['url'] = $this->url( $node, $mag->url );
        $item['picture'] = $this->picture( $node, $mag->url );
        $item['name'] = $this->db->real_escape_string( $this->name( $node ) );
        $item['price'] = $this->price( $node );
        if ($item['price']==""){
            echo "<br/>".$item['url']."<br>";
        }
        $item['price_old'] = $this->price_old( $node );
        $item['sale'] = $this->db->real_escape_string( $this->sale( $node ) );
        
        // переходим на страницу подробней (получаем все фото, размер, ...)
        $dom = new DOMDocument();
        $source = mb_convert_encoding($this->file_get_contents_curl( $item['url'] ), 'HTML-ENTITIES', 'utf-8');
        $dom->loadHTML( $source );

        $attr['size'] = $this->size( $dom );

        $item['description'] = $this->db->real_escape_string( $this->description( $dom ) );
        $item['articul'] = $this->db->real_escape_string( $this->articul( $dom ) );
        if($item['articul']===null)
            $item['articul'] = $this->db->real_escape_string( $this->articulByUrl( $item['url'] ) );

        $item['hash'] = md5( $item['id_mag'].$item['url'].$item['name'].$item['articul'] );
        
        $pictures = $this->pictures( $dom, $mag->url );
        
        if ( $item['picture'] == '' ) $item['picture'] = $pictures[0];
        
        $params = array();
        if ( method_exists($this,"brand")) $params['brand'] = $this->brand($dom);

//var_dump( $item, $attr, $pictures );
//exit();
        if ( !($item['price'] == '' && $item['articul'] == '') )
            $this->insert( $item, $attr, $pictures,$params );
    }
    private function printt($item){
        echo "<li>".$item->name." ".$item['url']." ".$item['price']." ".$item['articul']."</li>";
    }
    private function insert( $item, $attr, $pictures,$params )
    {
        $this->log->saveItem($item);
        if ( ($id = $this->db->select( 'SELECT id FROM catalog_items WHERE hash=\''.$item['hash'].'\'' )->current('id')) !== false )
        {
            $this->db->update( 'catalog_items', array( 'price' => $item['price'], 
                                                       'price_old' => $item['price_old'], 
                                                       'sale' => $item['sale'], 
                                                       'hide' => 'false', 
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
                if(isset($params['brand'])){
                    $this->insert_params( $id, $params );
                }
            }
            
            return $id;
        }
    }
    
    private function insert_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            if ( $value !== null || $value !== '' )
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
    private function insert_params( $id, $item )
    {
            $this->db->insert( 'catalog_items_params', array('id_item'=>$id, 'field_name'=>'brand', 'field_value'=>$item['brand']) );

    }
    
    private function update_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            $this->db->update( 'catalog_attr', array( 'field_value'=>$value ), 'iditem='.$id.' AND field_name=\''.$key.'\'' );
        }
    }

    abstract protected function url( $node, $url='' );

    abstract protected function picture( $node, $url='' );

    abstract protected function name( $node );

    abstract protected function price( $node );

    abstract protected function price_old( $node );

    abstract protected function sale( $node );

    abstract protected function size( $node );

    abstract protected function pictures( $node );

    abstract protected function description( $node );

    abstract protected function articul( $node );
    
    protected function getElement( $node, $tagName, $class='' )
    {
        //var_dump($tagName, $class);

        if ( ($elements = $node->getElementsByTagName( $tagName )) !== null )
        {
            foreach ( $elements as $element )
            {
                if ( $class == '' )
                    return $element;


                if ( ($attr = $element->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == $class )
                    {
                        return $element;
                    }
                }
            }
        }
        return null;
    }
    
    protected function getElementValue( $node, $tagName, $class='' )
    {
        if ( ($element = $this->getElement( $node, $tagName, $class )) !== null )
        {
            return $element->nodeValue;
        }
        return '';
    }
    
    
    
    protected function getAttributValue( $node, $tagName, $attr )
    {
        if ( ($elements = $node->getElementsByTagName( $tagName )) !== null )
        {
            foreach ( $elements as $element )
            {
                if ( ($a = $element->attributes->getNamedItem( $attr )) !== null )
                {
                    return $a->value;
                }
            }
        }
        return '';
    }
}

?>