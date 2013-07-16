<?php

class TAdmin_offers extends TBAdmin
{

    public function getCategories(){ //выгрузка категории предложений
	    $res = $this->db->select('SELECT DISTINCT cat_name,cat_title FROM offers')->toObject();
	    $cat = array();
	    $cat['obj'] = $res;
	    foreach ($res as $r){ //приводим к нормальному виду массив
	    	$cat['normal'][$r->cat_name] = $r->cat_title;
	    }
	    return $cat;
    }
    public function getOffer( $id){
	    return $this->db->select( 'SELECT *,banks.id as bank_id, offers.id as offers_id, offers.description as descr FROM offers LEFT JOIN banks ON banks.id=offers.id_bank LEFT JOIN `rates` ON offers.id=rates.id_offer WHERE offers.id='.$id )->current();
    }
    
    public function getAllBanks(){
    	return $this->db->select("SELECT * FROM banks")->toObject();
    }
    
    public function edit($get, $post)
    {
    	$approvement_documents = json_encode($post->approvement_documents);
    	//запись в основну таблицу
        $this->db->update( 'offers', array('name'=>$post->name,'id_bank'=>$post->bank,'images'=>$post->image,'description'=>$post->descr,
        'approv_needed'=>$post->approv_needed,
        'time_to_consider'=>$post->time_to_consider,
        'is_capitalization'=>$post->is_capitalization,
        'is_replenishable'=>$post->is_replenishable,
        'capitalization_period'=>$post->capitalization_period,
        'approvement_documents'=>$post->approvement_documents
        ), 'id='.$get->id );
        
        //запись в таблицу rates
        $this->db->update( 'rates', array(
        'min_rate'=>$post->min_rate,
        'max_rate'=>$post->max_rate,
        'period'=>$post->period,
        'sum'=>$post->sum,
        'grace_period'=>$post->grace_period,
        'limit'=>$post->limit
        ), 'id_offer='.$get->id );
    }
    public function hideOffer($id){
	    //получаем предложение
	    $offer = $this->db->select("SELECT * FROM offers WHERE id=".$id)->current();
	    if($offer['published']==1){
		    $this->db->update('offers',array('published'=>0),'id='.$id);
	    }else{
		    $this->db->update('offers',array('published'=>1),'id='.$id);
	    }
    }
    public function del($get, $post){
	    if($post->id){
		    $this->db->query("DELETE FROM offers WHERE id=".$post->id);
		    $this->db->query("DELETE FROM rates WHERE id_offer=".$post->id);
	    }
    }
    public function add($get, $post){
    	//запись в основну таблицу
        $insertid = $this->db->insert( 'offers', array('name'=>$get->name,'id_bank'=>$get->bank,'images'=>$get->image,'description'=>$get->descr,
        'cat_name'=>$get->cat, 'url_form'=>$get->urlForm, 'published'=>$get->published
        ) );
        $res[] = $insertid;
        echo json_encode($res);
    }
    public function add_credits($get, $post){
	    //обновляем основную таблицу
	    $this->db->update( 'offers', array('name'=>$post->name,'id_bank'=>$post->bank,'url_form'=>$get->urlForm, 'published'=>$get->published,
        'approv_needed'=>$post->approv_needed,
        'time_to_consider'=>$post->time_to_consider,
        'approvement_documents'=>$post->approvement_documents
        ), 'id='.$get->id );
        
        //запись в таблицу rates
        $this->db->insert( 'rates', array(
        'min_rate'=>$post->min_rate,
        'max_rate'=>$post->max_rate,
        'period'=>$post->period,
        'sum'=>$post->sum,
        'id_offer'=>$get->id
        ));
    } 
    public function add_creditcards($get, $post){
	    //обновляем основную таблицу
	    $this->db->update( 'offers', array('name'=>$post->name,'id_bank'=>$post->bank,'url_form'=>$get->urlForm, 'published'=>$get->published,
        'approv_needed'=>$post->approv_needed,
        'time_to_consider'=>$post->time_to_consider,
        'approvement_documents'=>$post->approvement_documents
        ), 'id='.$get->id );
        
        //запись в таблицу rates
        $this->db->insert( 'rates', array(
        'min_rate'=>$post->min_rate,
        'max_rate'=>$post->max_rate,
        'grace_period'=>$post->grace_period,
        'limit'=>$post->limit,
        'id_offer'=>$get->id
        ));
    }
    public function add_deposits($get, $post){
	    //обновляем основную таблицу
	    $this->db->update( 'offers', array('name'=>$post->name,'id_bank'=>$post->bank,'url_form'=>$get->urlForm, 'published'=>$get->published,
        'is_capitalization'=>$post->is_capitalization,
        'is_replenishable'=>$post->is_replenishable,
        'capitalization_period'=>$post->capitalization_period
        ), 'id='.$get->id );
        
        //запись в таблицу rates
        $this->db->insert( 'rates', array(
        'min_rate'=>$post->min_rate,
        'max_rate'=>$post->max_rate,
        'period'=>$post->period,
        'sum'=>$post->sum,
        'id_offer'=>$get->id
        ));
    }   
}

?>