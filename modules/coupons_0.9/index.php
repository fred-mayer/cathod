<?php

class Tcoupons extends TModule
{
    public function display( TTemplate $template )
    {
        if ( $template->route[0] == $this->getName() && $this->set_pos == 'section' ) // Главный модуль
        {
            if ( isset($template->get->coupon_brand) )
            {
                $where = 'idbrand='.$template->get->coupon_brand->int();
            }
            elseif ( isset($template->get->coupon_type) )
            {
                $where = 'idtype='.$template->get->coupon_type->int();
            }
            elseif ( isset($template->get->coupon_category) )
            {
                $where = 'idcategory='.$template->get->coupon_category->int();
            }
            
           $where = isset($where) ? ' WHERE '.$where : '';

            $this->data = $this->db->select( 'SELECT * FROM coupon_material'.$where );
        }
        else
            $this->data = $this->db->select( 'SELECT * FROM coupon_material' );
        
        parent::display( $template );
    }

    public function getAdminToolbar( $attr, $buttons=null )
    {
        $buttons[] = array('action'=>'parser', 'icon'=>'asterisk', 'text'=>'Парсер', 'title'=>'Парсер файла');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>