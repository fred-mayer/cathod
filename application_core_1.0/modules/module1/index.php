<?php

//функции и вся логика модуля здесь? создается как расширенный класс модуля
class Tmodule1 extends TModule
{
    public function display( TTemplate $template )
    {
            
            // $template->db // Обращение к базе
            
		//функция отображения модуля - записи содержимого его и возврата в переменную
		$proba = array('1',2,3,4,5);
		//return $proba;
		/*
		ob_start();
		//выполняются нек функции...
		
		
		$buffer = ob_get_contents();
		ob_end_clean();
		*/
                
                $proba['href'] = 'localhost';
                $proba['link'] = 'local';
                
                $this->data = $proba;
                
        //include( TEMP_DIR.$template->getName().'/modules/'.$this->getName().'.php' );
        parent::display( $template );
    }
}
?>