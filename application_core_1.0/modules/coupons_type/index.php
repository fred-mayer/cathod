<?php

class Tcoupons_type extends TModule
{
    public function display( TTemplate $template )
    {
        $this->data = $this->db->select( 'SELECT * FROM coupon_type' );
        
        parent::display( $template );
    }
}

?>