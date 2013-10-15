<?php

class Tcatalog_basket extends TModule
{
    public function display( TTemplate $template )
    {
        $cookies = new TCookies();
        //if(isset($cookies->busket)){
           $coo = json_decode(stripslashes($cookies->busket));
           $countAll = 0;
           if(isset($cookies->busket)){
            foreach ($coo as $c){
                $countAll +=$c->count;
            }
           }
           $this->data['count'] = $countAll;
           $this->data['summ'] = $this->getSumCookItems();
           parent::display( $template );
        //}else{
            //echo $template->displaySystemMes("Корзина пуста");
        //}
    }
    /**
     * Функция возвращает сумму товаров в корзине
     * 
     * @return int
     */
    public function getSumCookItems(){
        $cookies = new TCookies();
        if(isset($cookies->busket)){
            $coo = json_decode(stripslashes($cookies->busket));
            $sum = 0;
            foreach($coo as $c){
                $sum+=$c->price*$c->count;
            }
            return $sum;
        }else{
            return false;
        }
    }
    public function getBasketJson(){
        $cookies = new TCookies();
        if(isset($cookies->busket)){
            $coo = json_decode(stripslashes($cookies->busket));
            $sum = 0;
            $count = 0;
            foreach($coo as $c){
                $sum+=$c->price*$c->count;
                $count +=$c->count;
            }
            $res = array('result'=>'ok','sum'=>$sum,'count'=>$count);
            echo json_encode($res);
        }else{
            $res = array('result'=>'false');
            echo json_encode($res);
        }
    }
    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'addCat', 'icon'=>'plus', 'text'=>'', 'title'=>'Добавить категорию');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>