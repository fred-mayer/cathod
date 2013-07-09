<?php

//Модуль вывода предложений по категориям и фильтр по банку
// $cat - латинское название категории - если пустое тогда береться с параметра страницы
// $bank - наименование банка
class Toffer_detail extends TModule
{
    public function display( TTemplate $template)
    {   
        // $template->db // Обращение к базе
        $params = $this->getParams(); //получение параметров
        if($params['id'] || $template->route[3]){
        	//выбираем id, через параметр главный если нет тогда по uri
	        $isParam = ($params['id'])? 1:0;
	        if($isParam){
		        $id=$params['id'];
		        $cat=$params['category'];
	        }else{
		        $id=$template->route[3];
		        $cat=$template->route[1];
	        }
	        
	        $this->data['result'] = $this->getDataTable($template, $cat, $id);
	        $this->data['result'] = $this->resultProcessing($template, $this->data['result'], $cat, $params['linkPage'], $params['linkPageDetail'], $params['linkPageForm']);
	        
	        if(!isset($params['title'])): //записываем title
		        $params['title'] = $this->data['result']['name'];
			    $params['title'] .= ' - '.$this->data['result']['bank_name'];
	        endif;
	        $this->setTitle($params['title']);
	        
	        parent::display( $template );
        }
    }
    public function getDataTable(TTemplate $template, $cat, $id){
	    $sql = 'SELECT *,cat.id as id_offer, bank.id as id_bank, cat.description as descr FROM offer_'.$cat. ' AS cat LEFT JOIN `banks` as bank ON cat.id_bank = bank.id WHERE cat.id='.$id;
	    return $template->db->select($sql)->current();
    }
    public function setTitle($title){
	    $this->data['title'] = $title;
    }
    public function resultProcessing(TTemplate $template, $data, $category, $linkPage='', $linkPageDetail='', $linkPageForm = ''){ //функция обработки данных, формирование ссылок...
    		//формирование ссылок
		    $pageD = $template->route->getCurrentPage();
		    $pageF = $template->route->getCurrentPage();
	    	if($linkPage){
	    		$pageD = $linkPage;
	    		$pageF = $linkPage;
	    	}
	    	if($linkPageDetail){
		    	$pageD = $linkPageDetail;
	    	}
	    	if($linkPageForm){
		    	$pageF = $linkPageForm;
	    	}
	    	
	    	$bank = $data->alias;
	    	$idOffer = $data->id_offer;
		    $data->linkOffer = "/".$pageD."/".$category."/".$bank."/".$idOffer;
		    $data->linkForm = "/".$pageF."/".$category."/".$bank."/".$idOffer . "?action=form";
		    //формирование полей - !!!оформить глобальными функциями
		    //мин. ставка
		    $data->min_rate = str_replace('.',',',$data->min_rate).'%';
		    //макс сумма
		    $data->max_sum = number_format($data->max_sum,0,","," ").' руб.';
		    //макс срок
		    $data->max_period = ($data->max_period >60)? ($data->max_period/12).' лет':$data->max_period.' мес.';
		    //срок рассмотрения
		    switch($data->cons_aplic){
			    case 0:
			    	$data->cons_aplic = 'день в день';
			    	break;
			    case 1:
			    	$data->cons_aplic = 'за 1 день';
			    	break;
			    case ($data->cons_aplic<5):
			    	$data->cons_aplic = 'за '.$data->cons_aplic.' дня';
			    	break;
			    case ($data->cons_aplic>=5):
			    	$data->cons_aplic = 'за '.$data->cons_aplic.' дней';
			    	break;
			    	
		    }
	    return $data;
    }
}
?>