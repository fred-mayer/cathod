<?php

//Модуль вывода предложений по категориям и фильтр по банку
// $cat - латинское название категории - если пустое тогда береться с параметра страницы
// $bank - наименование банка
class Tmoffers extends TModule
{
    public function display( TTemplate $template)
    {
            
            // $template->db // Обращение к базе
        $params = $this->getParams();
        if(is_array($params)) extract($params);
        
        if(!isset($bank) && !isset($category) && isset($template->route[2])){ //если параметр  $bank не задан тогда берем с uri
	        $bank = $template->route[2];
        }
        
        if(!isset($category) && isset($template->route[1])){ //если параметр  $cat не задан тогда берем с uri
	        $category = $template->route[1]; 
        }
        
        //проверяем есть ли такая категория
        $existCat = $template->db->exists('SELECT id FROM offer_'.$category .' LIMIT 1');
        
        if($existCat):
	        $sql = 'SELECT * FROM offer_'.$category. ' AS cat';
	        if(isset($bank)){
		        $sql.= ' LEFT JOIN `banks` as bank ON cat.id_bank = bank.id WHERE bank.alias="'.$bank.'"';
	        }
	        if(isset($order)){
		        $sql.= ' ORDER BY cat.'.$order . ' ASC';
	        }else{
		        $sql.= ' ORDER BY cat.max_sum ASC';
	        }
	        $this->data = $template->db->select($sql)->toObject();
	        
        else:
        	$this->data = 0;
        endif;
        parent::display( $template );
    }
}
?>