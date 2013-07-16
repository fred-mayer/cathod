<?php

class Tcoupons_category extends TModule
{
    public function display( TTemplate $template )
    {
        $this->data = $this->db->select( 'SELECT * FROM coupon_category WHERE parent_id=0' );
        
        parent::display( $template );
    }
}

?>