<?php

class Tadmin_coupons extends TBAdmin
{
    protected $xml;

    public function parser( $get, $post )
    {
        if ( isset($_FILES['file']) )
        {
            $tmp_filename = $_FILES['file']['tmp_name'];
            
            if ( is_uploaded_file( $tmp_filename ) )
            {
                
                $this->xml = new simple_html_dom();
                $this->xml->load_file( $tmp_filename );
                
                $filter = $this->xml->find( 'filter' );
                
                $this->parser_type( $filter[0] );
                $this->parser_brand( $filter[0] );
                
                
                $this->db->query( 'DELETE FROM coupon_category' );
                
                $this->parser_category( $filter[0] );
                
                
                $coupons = $this->xml->find( 'coupons' );
                
                $this->parser_material( $coupons[0] );
            }
        }
    }
    
    protected function parser_type( $filter )
    {
        $ctype = new simple_html_dom();
        $ctype->load( $filter->innertext );

        $ctypes = $ctype->find( 'ctype' );
        
        $this->db->query( 'DELETE FROM coupon_type' );

        foreach ( $ctypes as $item )
        {
            //echo 'id = '.$item->getAttribute( 'id' ).'; title='.$this->iconv( $item ).'<br>';
            
            $this->db->query( 'INSERT INTO coupon_type(id, title) VALUES ('.$item->getAttribute( 'id' ).', \''.$this->iconv( $item->innertext ).'\')' );
        }
    }
    
    protected function parser_brand( $filter )
    {
        $brand = new simple_html_dom();
        $brand->load( $filter->innertext );

        $brands = $brand->find( 'brand' );
        
        $this->db->query( 'DELETE FROM coupon_brand' );

        foreach ( $brands as $item )
        {
            $this->db->query( 'INSERT INTO coupon_brand(id, title) VALUES ('.$item->getAttribute( 'id' ).', \''.$this->iconv( $item->innertext ).'\')' );
        }
    }
    
    protected function parser_category( $filter )
    {
        $category = new simple_html_dom();
        $category->load( $filter->innertext );

        $categorys = $category->find( 'categories' );

        foreach ( $categorys as $item )
        {
            $this->parser_category_children( $item );
        }
    }
    
    protected function parser_category_children( $item, $parent_id=0 )
    {
        foreach ( $item->children as $children )
        {
            if ( isset($children->value) )
                $title = $children->value;
            else
                $title = $children->innertext;

            
            $this->db->query( 'INSERT INTO coupon_category(id, title, parent_id) 
                VALUES('.$children->id.', \''.$this->iconv( $title ).'\', '.$parent_id.')' );
            

            if ( count($children->children) > 0 ) $this->parser_category_children( $children, $children->id );
        }
    }
    
    protected function parser_material( $coupons )
    {
        $material = new simple_html_dom();
        $material->load( $coupons->innertext );

        $materials = $material->find( 'material' );
        
        $this->db->query( 'DELETE FROM coupon_material' );

        foreach ( $materials as $item )
        {
            $desc = rtrim( ltrim( $this->get( $item, 'desc' ), '<![CDATA['), ']]>');
            $image = $this->get( $item, 'image' );
            
            $this->db->query( 'INSERT INTO coupon_material(id, idtype, idbrand, idcategory, title, `desc`, img, date_from, date_to, promocode) 
                VALUES('.$item->getAttribute( 'id' ).', 
                    '.$this->attr( $item, 'type', 'id' ).', 
                    0, 
                    0, 
                    \''.$this->get( $item, 'title' ).'\', 
                    \''.$desc.'\', 
                    \''.$image.'\', 
                    \''.$this->get( $item, 'date_from' ).'\', 
                    \''.$this->get( $item, 'date_to' ).'\', 
                    \''.$this->get( $item, 'promocode' ).'\')' );
            
            if ( $image !== '' )
                file_put_contents( 'media/coupon/'.$image, file_get_contents( 'http://mixmarket.biz/images/um/'.$image ) );
        }
    }
    
    protected function get( $item, $name )
    {
        $i = $item->find( $name );
        return $this->iconv( $i[0]->innertext );
    }
    
    protected function attr( $item, $name, $attr )
    {
        $i = $item->find( $name );
        return $this->iconv( $i[0]->getAttribute( $attr ) );
    }

    protected function iconv( $text )
    {
        return iconv( 'windows-1251', 'utf-8', $text );
    }
}

?>