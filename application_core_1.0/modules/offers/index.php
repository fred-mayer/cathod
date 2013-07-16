<?php

//Модуль вывода предложений по категориям и фильтр по банку
// $cat - латинское название категории - если пустое тогда береться с параметра страницы
// $bank - наименование банка
class Toffers extends TModule
{
	protected $categories = array('credits','creditcards');//категории
    public function display( TTemplate $template)
    {
            
        // $template->db // Обращение к базе
        $params = $this->getParams();
        if(!isset($params['category']) && $template->route[3]){ //проверяем ссылка на страницу предложения?
        	if($template->get->action == 'form'){
	        	// страница формы отправки заявки
	        	//$template->db->select()->current();
	        	$offersForm_m = $this->getModule('offer_form'); //загружаем модуль формы - общий
	        	$offersForm_m->display($template);	
        	}else{
        		// подробная страница предложения
	        	$offersDetail_m = $this->getModule('offer_detail'); 
	        	$offersDetail_m->display($template);	
        	}
        }else{
	       $this->modelOffers($template); //загрузка модели предложений
        }      
    }
    function modelOffers(TTemplate $template){ //модель предложений
	    $params = $this->getParams();
        if(is_array($params)) extract($params); //преобразуем массив в переменные
        
        if(!isset($isFormBut) && $isFormBut!==0){ //кнопка оформить
	       $this->data['isFormBut'] = 1; 
        }
        
        if(!isset($bank) && !isset($category) && $template->route[2]){ //если параметр  $bank не задан тогда берем с uri
	        $bank = $template->route[2];
        }
        
        if(!isset($category) && isset($template->route[1])){ //если параметр  $cat не задан тогда берем с uri
	        $category = $template->route[1]; 
        }
        
        if(!isset($category)){ //Когда нет категории тогда выборка по избранным
	        $params['isSpecial'] = 1;
        }
        if(!isset($bank)){ $bank=''; }
        if(!isset($order)){ $order=''; }        
	    //echo $sql;
	    if(isset($category)){ //проверяем есть ли категория
	    	$this->module_template = $category; // записываем шаблон
	    	$this->data['category'] = $category;
	    	$this->data['result'] = $this->getDataTable($template, $category, $bank,$order,$params); // получение данных из бд
	    	
	    }elseif(isset($params['isSpecial']) && !isset($category)){ //проверяем если категории нет тогда выбираем только спецпредложения
	    	$this->data['result'] = array();
		    foreach($this->categories as $category){
		    	$result = $this->getDataTable($template, $category, $bank,$order,$params);
		    	if($result!==0){
			    	$this->data['result'] = array_merge($this->data['result'],$result);
		    	}
		    }
	    }
	     //записываем данные
	    if($this->data['result']!==0):    
	        $this->data['result'] = $this->resultProcessing($template,$this->data['result'], $category,$linkPage,$linkPageDetail,$linkPageForm);
	        if(!isset($title)): //записываем title
		        $title = $this->data['result'][0]['cat_title'];
		        if($bank){
			        $title .= ' - '.$this->data['result'][0]['bank_name'];
		        }
	        endif;
	        $this->setTitle($title);
        else:
        	$this->data = 0;
        endif;
        
        
        parent::display( $template );
    }
    public function getDataTable(TTemplate $template, $category,$bank='',$order='',$params){
	    $existCat = $template->db->exists('SELECT id FROM offers WHERE cat_name="'.$category.'" LIMIT 1');
        if($existCat){ //проверяем есть ли такая категория
	    	  //блок фильтра
        	if(isset($params['isFilter']) && isset($params['isMain']) && !isset($params['isSpecial'])){
	        	$this->data['isFilter'] = $params['isFilter']; //отмечаем для шаблона что доп фильтры есть
	        	if($template->get->submit=='ок' && (isset($template->get->rate) || isset($template->get->sum) || isset($template->get->period))){ //  проверяем был ли запрос отправлен
	        		// загрузка разного фильтра для разных категорий
	        		if($category=='credits'){
		        		$fsql = $this->getFilterCredits($template);
	        		}
	        		if($category=='creditcards'){
		        		$fsql = $this->getFilterCreditcards($template);
	        		}
	        		if($category=='deposits'){
		        		$fsql = $this->getFilterDeposits($template);
	        		}
	        		$this->data['isFilterSubmit'] = 1; //записываем если фильтр используют
	        	}
        	}
        	$sql = 'SELECT *,cat.id as id_offer, bank.id as id_bank, cat.description as descr  FROM offers AS cat LEFT JOIN `banks` as bank ON cat.id_bank = bank.id LEFT JOIN `rates` as r ON cat.id=r.id_offer WHERE  cat.cat_name="'.$category.'"' ;
        	if ( !$template->auth->isAuthorized ) echo ' AND cat.published=1'; //если админ показывать все
	        if($bank){
		        $sql.= ' AND bank.alias="'.$bank.'"';
	        }
	        if(isset($params['isSpecial'])){
		        $sql .= ' AND cat.special=1';
	        }
	        if(isset($fsql)){ //вставляем запрос фильтра в основной запрос
	        	$sql.= $fsql;
	        }
	        if($order){
		        $sql.= ' ORDER BY cat.'.$order;
	        }else{
		        $sql.= ' ORDER BY cat.id ASC';
	        }
	        return $template->db->select($sql)->toObject()->toArray();
        }else{
	        return 0;
        }
    }
    public function setTitle($title){
	    $this->data['title'] = $title;
    }
    public function resultProcessing(TTemplate $template, $data, $category, $linkPage='', $linkPageDetail='', $linkPageForm = ''){ //функция обработки данных, формирование ссылок...
	    for($i=0;$i<count($data);$i++){
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
	    	
	    	$bank = $data[$i]['alias'];
	    	$idOffer = $data[$i]['id_offer'];
		    $data[$i]['linkOffer'] = "/".$pageD."/".$category."/".$bank."/".$idOffer;
		    $data[$i]['linkForm'] = "/".$pageF."/".$category."/".$bank."/".$idOffer . "?action=form";
		    
		    //формирование полей - !!!оформить глобальными функциями
		    //мин. ставка
		    $data[$i]['min_rate'] = str_replace('.',',',$data[$i]['min_rate']).'%';
		    $data[$i]['max_rate'] = str_replace('.',',',$data[$i]['max_rate']).'%';
		    //макс сумма
		    $data[$i]['sum'] = number_format($data[$i]['sum'],0,","," ").' руб.';
		    $data[$i]['limit'] = number_format($data[$i]['limit'],0,","," ").' руб.';
		    //макс срок
		    $data[$i]['period'] = ($data[$i]['period']>60)? ($data[$i]['period']/12).' лет':$data[$i]['period'].' мес.';
		    
		    $data[$i]['grace_period'] = $data[$i]['grace_period'] . ' д.';
		    //срок рассмотрения
		    switch($data[$i]['time_to_consider']){
		    	case -1: unset($data[$i]['time_to_consider']); break;
			    case 0:
			    	$data[$i]['time_to_consider'] = 'день в день';
			    	break;
			    case 1:
			    	$data[$i]['time_to_consider'] = 'за 1 день';
			    	break;
			    case ($data[$i]['time_to_consider']<5):
			    	$data[$i]['time_to_consider'] = 'за '.$data[$i]['time_to_consider'].' дня';
			    	break;
			    case ($data[$i]['time_to_consider']>=5):
			    	$data[$i]['time_to_consider'] = 'за '.$data[$i]['time_to_consider'].' дней';
			    	break;
			 //подтверждение дохода
			}
			switch($data[$i]['approv_needed']){
				 case 1: $data[$i]['approv_needed'] = 'да'; break;
				 case ($data[$i]['approv_needed']===0): $data[$i]['approv_needed']='нет'; break;
				 case "-1": $data[$i]['approv_needed']='на усмотрение банка'; break;
			}
			$apDoc = json_decode($data[$i]['approvement_documents']);
			$apDocData = array(1=>'другие документы, кроме справок о доходах', 2=>'справка 2-НДФЛ', 3=>'справка от работодателя по форме банка / в свободной форме',4=>'косвенное подтверждение доходов');
			if(count($apDoc)>0 && $data[$i]['approv_needed']!='нет'){
				foreach($apDoc as $ap){
					$data[$i]['approv_needed'] .= ", ". $apDocData[$ap];
				}
			}
			
			switch($data[$i]['is_capitalization']){
				case 1: $data[$i]['is_capitalization']='да'; break;
				case ($data[$i]['is_capitalization']==='0'): $data[$i]['is_capitalization']='нет'; break;
				default: $data[$i]['is_capitalization']='на выбор';
			}
			if($data[$i]['is_replenishable']==1){
				$data[$i]['is_replenishable'] = "да";
			}else{
				$data[$i]['is_replenishable'] = "нет";
			}
			switch($data[$i]['capitalization_period']){
				case 1: $data[$i]['capitalization_period'] = 'в конце срока'; break;
				case 2: $data[$i]['capitalization_period'] = 'проценты вперёд'; break;
				case 3: $data[$i]['capitalization_period'] = 'ежедневно'; break;
				case 4: $data[$i]['capitalization_period'] = 'еженедельно'; break;
				case 5: $data[$i]['capitalization_period'] = 'ежемесячно'; break;
				case 6: $data[$i]['capitalization_period'] = 'ежеквартально'; break;
				case 7: $data[$i]['capitalization_period'] = 'раз в полгода'; break;
				case 8: $data[$i]['capitalization_period'] = 'ежегодно'; break;
			}
	    }
	    return $data;
    }
    function getFilterCredits(TTemplate $template){
	    $fsql = ' AND';
	        		if($template->get->rate->int()){
		        		$fsql .=' r.min_rate<='.$template->get->rate.' AND'; 
	        		}
	        		if($template->get->sum->int()){
		        		$fsql .=' r.sum>='.$template->get->sum->int().' AND'; 
	        		}
	        		if($template->get->period->int()){
		        		$fsql .=' r.period>='.$template->get->period->int().' AND'; 
	        		}
	        		
	        		
	        		return substr($fsql,0,-3);// удаляем последний AND
    }
    function getFilterCreditcards(TTemplate $template){
	    $fsql = ' AND';
	        		if($template->get->rate->int()){
		        		$fsql .=' r.min_rate<='.$template->get->rate.' AND'; 
	        		}
	        		if($template->get->limit->int()){
		        		$fsql .=' r.limit>='.$template->get->limit->int().' AND'; 
	        		}
	        		if($template->get->period->int()){
		        		$fsql .=' r.grace_period>='.$template->get->period->int().' AND'; 
	        		}
	        		
	        		
	        		return substr($fsql,0,-3);// удаляем последний AND
    }
    function getFilterDeposits(TTemplate $template){
	    $fsql = ' AND';
	        		if($template->get->rate->int()){
		        		$fsql .=' (r.min_rate>='.$template->get->rate.' OR r.max_rate>='.$template->get->rate.') AND'; 
	        		}
	        		if($template->get->sum->int()){
		        		$fsql .=' r.sum>='.$template->get->sum->int().' AND'; 
	        		}
	        		if($template->get->period->int()){
		        		$fsql .=' r.gperiod>='.$template->get->period->int().' AND'; 
	        		}
	        		
	        		
	        		return substr($fsql,0,-3);// удаляем последний AND
    }

}
?>