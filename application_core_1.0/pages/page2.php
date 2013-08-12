<?php
//http://localhost:8888/page2
set_time_limit( 1200 );

class TParse extends TXML_handler_tag
{
    private $db;

    private $adv = array();
    private $brand = array();
    private $category = array();
    
    private $type;
    private $name;
    private $url;
    private $desc;
    private $price;
    
    public $date_begin;
    public $count_offer = 0;
    public $count_offer_insert = 0;
    public $count_offer_update = 0;
    
    public function __construct( TMySQL $db )
    {
        $this->db = $db;
        $this->date_begin = date('Y-m-d H:i:s');

        parent::__construct();
    }

    public function adv( $attr, $data, $parent )
    {
        $this->adv[ $attr['ID'] ] = $data;
    }

    public function advertizers( $attr, $data, $parent )
    {
        foreach ( $this->adv as $key => $value )
        {
            $this->db->insert( 'advertiser', array( 'id' => $key, 'name' => $value), 'IGNORE' );
        }
    }

    public function brand( $attr, $data, $parent )
    {
        $this->brand[ $attr['ID'] ] = $data;
    }

    public function brands( $attr, $data, $parent )
    {
        foreach ( $this->brand as $key => $value )
        {
            $this->db->insert( 'brand', array( 'id' => $key, 'name' => $value), 'IGNORE' );
        }
    }
    
    public function category( $attr, $data, $parent )
    {
        $this->category[ $attr['ID'] ] = array('name'=>$data, 'parent_id'=>$attr['PARENTID']);
    }
    
    public function categories( $attr, $data, $parent )
    {
        foreach ( $this->category as $key => $value )
        {
            $this->db->insert( 'category', array( 'id' => $key, 'name' => $value['name'], 'parent_id' => $value['parent_id']), 'IGNORE' );
        }
    }
    
    public function type( $attr, $data, $parent )
    {
        $this->type = $data;
    }
    
    public function name( $attr, $data, $parent )
    {
        $this->name = $data;
    }
    
    public function url( $attr, $data, $parent )
    {
        $this->url = $data;
    }
    
    public function desc( $attr, $data, $parent )
    {
        $this->desc = $data;
    }
    
    public function price( $attr, $data, $parent )
    {
        $this->price = $data;
    }
    
    public function offer( $attr, $data, $parent )
    {
        $hash = md5( $this->name.$this->desc.$this->price );


        if ( !$this->db->exists( 'SELECT id FROM offer WHERE id='.$attr['ID'] ) )
        {
            $this->db->insert( 'offer', array( 'id' => $attr['ID'], 
                                               'idbrand' => $attr['BRANDID'], 
                                               'idadvertiser' => $attr['ADVID'], 
                                               'idcategory' => $attr['CAT'], 
                                               'type' => $this->type, 
                                               'name' => $this->name, 
                                               'desc' => $this->desc, 
                                               'img' => $attr['SRC'], 
                                               'url' => $this->url, 
                                               'price' => $this->price, 
                                               'hash' => $hash, 
                                               'update' => '') );

            $this->count_offer_insert++;
        }
        elseif ( !$this->db->exists( 'SELECT id FROM offer WHERE id='.$attr['ID'].' AND hash=\''.$hash.'\'' ) )
        {
            $this->db->update( 'offer', array( 'idbrand' => $attr['BRANDID'], 
                                               'idadvertiser' => $attr['ADVID'], 
                                               'idcategory' => $attr['CAT'], 
                                               'type' => $this->type, 
                                               'name' => $this->name, 
                                               'desc' => $this->desc, 
                                               'img' => $attr['SRC'], 
                                               'url' => $this->url, 
                                               'price' => $this->price, 
                                               'hash' => $hash, 
                                               'update' => date('Y-m-d H:i:s') ), 'id='.$attr['ID'] );

            $this->count_offer_update++;
        }



        if ( isset($this->category_brand[ $attr['CAT'] ][ $attr['BRANDID'] ]) )
            $this->category_brand[ $attr['CAT'] ][ $attr['BRANDID'] ]++;
        else
            $this->category_brand[ $attr['CAT'] ][ $attr['BRANDID'] ] = 1;



        $this->count_offer++;
    }
    
    public function offers( $attr, $data, $parent )
    {
        foreach ( $this->category_brand as $key_idcategory => $brand )
        {
            foreach ( $brand as $key_idbrand => $value_count_offer )
            {
                $this->db->query( 'INSERT INTO category_brand (idcategory, idbrand, count_offer) 
                                        VALUES ('.$key_idcategory.', '.$key_idbrand.', '.$value_count_offer.') 
                                            ON DUPLICATE KEY UPDATE count_offer='.$value_count_offer );
            }
        }
        
        
        $this->db->insert( 'parse', array( 'count_offer' => $this->count_offer, 
                                           'count_offer_insert' => $this->count_offer_insert, 
                                           'count_offer_update' => $this->count_offer_update, 
                                           'date_begin' => $this->date_begin) );
    }
}


$parse = new TParse( $template->db );

$parse->parseFile( '/Users/mizko/Documents/www/application_core_1.0/pages/dn.xml' );


var_dump( $parse->count_offer );
var_dump( $parse->count_offer_insert );
var_dump( $parse->count_offer_update );

exit();

?>