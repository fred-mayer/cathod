<?php

class Tcoupons_brand extends TModule
{
    public function display( TTemplate $template )
    {
        $this->data = $this->db->select( 'SELECT * FROM coupon_brand' );
        
        parent::display( $template );
    }
}

?>