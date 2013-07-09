<?php

//функции и вся логика модуля здесь? создается как расширенный класс модуля
class Tmodule2 extends TModule
{
    public function display( TTemplate $template )
    {
            
            
                
                $this->data = 'public function display( TTemplate $template )';
                
        //include( TEMP_DIR.$template->getName().'/modules/'.$this->getName().'.php' );
        parent::display( $template );
    }
}
?>