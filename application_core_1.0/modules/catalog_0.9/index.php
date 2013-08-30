<?php

class Tcatalog extends TModule
{
    protected $pagination = array();
    public function display( TTemplate $template )
    {
		$db = $template->db;
        $params = $this->getParams();
        $data['idmodule'] = $this->idmodule;
		//загрузка магазинов с бд
		$data['magazines']=$db->select("SELECT * FROM catalog_magazine")->toObject();
        if($params['id']){
	        //если id указано показывай товары
	        $data['id'] = $params['id'];
	        $data['items'] = $this->getItems($data['id']);
        }else{
	        //если не указано выводи информацию
        }
        $this->data = $data;
        if ( $template->route[0] == $this->getName() && $this->set_pos == 'section' ) // Главный модуль
        {
             //если есть категория тогда найди все подкатегории и проверь есть ли id товара   
             $route = $this->route($template->route); //возвращает массив, idItem - id товара, idCats - alias категории
             switch($template->route[1]){ //выборка по страницам
                 case "basket":
                     //страница корзины
                     $this->data['items'] = $this->getBasket();
                     $this->data['module'] = $this;
                     $this->module_template = "basket"; //записали шаблон отображения
                     parent::display( $template );
                 break;
                 case "order":
                     //страница оформления покупки
                     $this->data['items'] = $this->getBasket();
                     $this->data['module'] = $this;
                     $this->data['form'] = new TForm();
                     if ($this->post->email){
                        $res = $this->addOrder();
                        $this->module_template = "order_done"; //записали шаблон отображения
                        parent::display( $template );
                     }else{
                        $this->order($this->data['form']);
                        $this->module_template = "order"; //записали шаблон отображения
                        parent::display( $template );
                     }
                 break;
                 default:
                     if(isset($route['idItem'])){ //страница товара
                            $this->pagination = array();
                            $this->getCatBreadcrumbs(count($route['idCats'])-1);
                            $this->breadcrumbs = array_reverse($this->breadcrumbs);
                            $this->data['breadcrumbs'] = $this->breadcrumbs;
                         
                            $item = $this->getItem($route['idItem']);
                            $this->data['item'] = $item;
                            $this->data['module'] = $this;
                            $this->module_template = "item"; //записали шаблон отображения
                            parent::display( $template );
                        }elseif(isset($route['idCats'])){ //страница категории
                            $items = $this->getCatItems($route);
                            if(count($items)>1){
                                   $this->data['items'] = $items;
                                   $this->module_template = "category"; //записали шаблон отображения
                                   parent::display( $template );
                            }else{
                                   if(!$template->auth->isAdmin)
                                           echo $template->displaySystemMes('К сожалению в этой категории нет товаров.');
                                   else
                                           echo $template->displaySystemMes('К сожалению в этой категории нет товаров. Возможно вам необходимо запустить парсер!');
                            }
                        }else{
                            //главная страница магазина
                            // надо придумать что здесь отображать) может ничего?
                        }
             }
             
         }

    }
    /**
     * функция возвращения списка товаров по uri - catalog/category/subcstegiry/id-item
     * 
     * @param array $route массив
     * @return array Возвращает список товаров
     */
    public function getCatItems($route){  
        $db = $this->template->db;
        //Считываем категорию
        $ii = count($route['idCats'])-1;
        $alias = $route['idCats'][$ii];
        $cat = $db->select("SELECT * FROM catalog_cats WHERE `hide`=1 && `alias`='$alias'")->current();
        $this->data['cat'] = $cat;       
        
        //Создаем breadcrumbs
        $this->pagination = array();
        $this->getCatBreadcrumbs($cat->id);
        $this->breadcrumbs = array_reverse($this->breadcrumbs);
        $this->data['breadcrumbs'] = $this->breadcrumbs;
        
        //устанавливаем title *** не работает надо решить!!!!
        $this->template->setTitle($cat->name);
        
        //Считываем товары
        $sql = "SELECT i.*,m.name as mag_name,m.trekking_url,m.logo,a.field_value as sizes
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.id_mag=m.id LEFT JOIN catalog_attr AS a ON i.id=a.iditem
            WHERE i.id_cat=".$cat->id." AND a.field_name='size' AND i.hide='false' AND m.hide=1";
        if(isset($route['idItem']))
            $sql .=" AND i.id=".$route['idItem']; //если показываем один товар
        $sql .= " ORDER BY i.sale DESC"; //*** добавить разделы страниц
        $items = $db->select($sql)->toObject();
        if(count($items)>0){
            for($i=0;$i<count($items);$i++){
                //добавляем рубли к ценам
                $currency = ($items[$i]->currencyid)? $items[$i]->currencyid:' <span class="currency">р</span>';
                $items[$i]->price .= $currency;
                $items[$i]->price_old .= $currency;

                //переводим размеры в массив
                $size = json_decode($items[$i]->sizes);
                $items[$i]->size = $size;
                
                //ссылка для страницы товара
                $items[$i]->item_url = DS.$this->getName().DS.implode($route['idCats'],"/").DS.$items[$i]->id;
            }
        }else{
            //пробуем найти товары в подкатегориях
            $catsChild = $db->select("SELECT id FROM catalog_cats WHERE `hide`=1 && `parentid`='".$this->data['cat']->id."'")->toObject();
            $childIds = "";
            foreach($catsChild as $child){
                $childIds .= ",".$child['id'];
            }
            $childIds = substr($childIds,1);
            $items = $this->getItems($childIds,20);
        }
        if(count($items)==1){ //если показываем один товар
            $items = $items[0];
        }
        return $items;
    }
    /**
     * Возвращает информацию о товаре по ид
     * 
     * @param integer $id ид товара
     * @return object возвращает информацию о товаре
     */
    private function getItem($id){ //информация о товаре
        $sql = "SELECT i.*,m.name as mag_name,m.trekking_url,m.logo,c.name as catname 
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.id_mag=m.id LEFT JOIN catalog_cats as c ON i.id_cat=c.id
            WHERE i.id=".$id." AND i.hide='false' AND m.hide=1";
        $item = $this->template->db->select($sql)->current();
        $this->formateItem($item);
        return $item;
    }
    /**
     * Функция форматирует взятые с базы поля товара
     * 
     * @param object $item товар в виде объекта
     */
    private function formateItem(& $item){ // форматирование данных о товаре
        //добавляем рубли к ценам
        $currency = ($item->currencyid)? $item->currencyid:' <span class="currency">р</span>';
        $item->price_int = $item->price;
        $item->price_old_int = $item->price_old;
        $item->price .= $currency;
        $item->price_old .= $currency;
        
        //добавляем url для товара
        if(!$item->item_url){
            $cats_tree = array_reverse($this->getCatsRecruse($item->id_cat));
            $item->item_url = DS.$this->getName().DS;
            foreach($cats_tree as $cat){
                $item->item_url .= $cat->alias .DS;
            }
            $item->item_url .= $item->id;
        }
        
        //Получаем аттрибуты товара
        $sql = "SELECT * FROM catalog_attr WHERE iditem=".$item->id;
        $attrs = $this->template->db->select($sql)->toObject();
        
        $attr_trans = array("size"=>"Размер");
        $i=0;
        $item->attrs = array();
        foreach($attrs as $attr){
            $item->attrs[$i] = new stdClass();
            $item->attrs[$i]->field_name = $attr_trans[$attr->field_name];
            $item->attrs[$i]->field_values = json_decode($attr->field_value);
            $i++;
        }
        
        //Получаем фотографии товара
        $sql = "SELECT * FROM catalog_pictures WHERE iditem=".$item->id." ORDER BY `id` ASC";
        $images = $this->template->db->select($sql)->toObject();
        $item->images = array();
        foreach($images as $image){
            $item->images[] = $image->picture;
        }
    }
    
    /**
     * Функция возвращает сумму купленных товаров
     * 
     * @param object $items
     * @return int возвращает сумму товаров
     */
    function getSumItems($items){
        $summ = 0;
        foreach($items as $item){
            $summ += $item->price_int*$item->count;
        }
        return $summ;
    }
    /**
     * Получение списка категорий в обратном порядке - т.е. родителей
     * 
     * @param integer $catid ид категории
     * @param array $tree рекрусивный параметр
     * @return array - получение обратного списка родителей
     */
    private function getCatsRecruse($catid,& $tree=''){ //получение категорий рекрусивно
        $result = $this->template->db->select( 'SELECT * FROM catalog_cats WHERE id='.$catid)->current();
        if($result->parentid>0){
            $tree[] = $result;
            return $this->getCatsRecruse($result->parentid,$tree);
        }else{
            $tree[] = $result;
            return $tree;
        }
    }
    public function printAttrsSelect($attrs){
        $i=0;
        foreach($attrs as $attr){
            ?>
                <p><strong><? echo $attr->field_name ?></strong>
                    <div class="btn-group attrs" rel="<? echo $attr->field_name ?>" data-toggle="buttons-radio">
                    <?
                    foreach($attr->field_values as $value){
                        ?>
                            <button type="button" class="btn"><? echo $value ?></button>
                        <?
                    }
                    ?>
                    </div>
                </p>
            <?
            $i++;
        }
    }
    public function printBasketButton($id){ //вывод кнопки корзины
        
        ?><a href="javascript:void(0)" rel="<? echo $id ?>" class="btn btn-large btn-primary basket-btn">В корзину</a><?
    }
    public function printScriptBasket(){ //выводит скрипт для добавленя в корзину
        ?>
        <script>
            $(".basket-btn").click(function(){
                var id = $(this).attr('rel');
                var size = $(".attrs .active").text();
                if(size){
                $.get("/ajax/catalog?action=addBasket&id="+id+"&size="+size,function(data) {
                    alert('Товар добавлен в корзину.');
                    $('.basket_module').updateBasket();
                });
                }else{
                    alert("Пожалуйста, выберите размер перед тем как добавить товар в корзину.");
                }
            });
        </script>
        <?
    }
    /**
     * addBasket - Функция добавления товара в корзину.
     * Передается через $get->id и добавляется в cookies
     * 
     * @param mixed $get
     * @param mixed $post
     */
    public function addBasket($get, $post){
        $cookies = new TCookies();
        //unset($cookies->busket);
        if(isset($cookies->busket)){
            $c = json_decode(stripslashes($cookies->busket));
        }else{
            $c = array();
        }
        $i = count($c);
        //проверяем есть ли такой товар в корзине
        $id = (string) $get->id;
        $key = false;
        $j = 0;
        foreach($c as $coo){
            if($id == $coo->id){
                $key = $j;
            }
            $j++;
        }
        if($key!==false){ //нашли значит просто увеличиваем количество
            $c[$key]->count= $c[$key]->count+1;
            if($c[$key]->size != (string) $get->size){
                $ii = count($c[$key]->size);
                $c[$key]->size[$ii] = (string) $get->size;
            }
        }else{
            $res = $this->template->db->select("SELECT price FROM catalog_items WHERE id=".$get->id)->current();
            $c[$i]['id'] = (string) $get->id;
            $c[$i]['price'] = $res->price;
            $c[$i]['size'][] = (string) $get->size;
            $c[$i]['count'] = 1;
            //$cookies->busket[$i]['params'] - параметры
        }
        $cookies->busket = json_encode($c);
        echo 'ok';
    }
    /**
     * Удаление товара с корзины
     * 
     * @param mixed $get
     * @param mixed $post
     */
    public function delBasket($get, $post){
        $cookies = new TCookies();
        if(isset($cookies->busket)){
            $c = json_decode(stripslashes($cookies->busket));
            $cnew = array();
            for($i=0;$i<count($c);$i++){
                if($c[$i]->id==$get->id){
                    //unset($c[$i]);
                }else{
                    $cnew[] = $c[$i];
                }
            }
            unset($cookies->busket);
            $cookies->busket = json_encode($cnew);
            $res['result'] = "ok";
            $res['sum'] = $this->getSumCookItems();
            echo json_encode($res);
        }else{
            $res['result']='false';
            echo json_encode($res);
        }
    }
    public function updateBasket($get, $post){
        $cookies = new TCookies();
        if(isset($cookies->busket)){
            $c = json_decode(stripslashes($cookies->busket));
            foreach($c as &$coo){
                if($coo->id==$get->id){
                    $coo->count = (string) $get->count;
                }
            }
            unset($cookies->busket);
            $cookies->busket = json_encode($c);
            $res['result'] = "ok";
            $res['sum'] = $this->getSumCookItems();
            echo json_encode($res);
        }else{
            $res['result'] = "false";
            echo json_encode($res);
        }
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
    /**
     * Возвращает товары в корзине
     * 
     * @return boolean or objects Если товаров в корзине нет возвращает строго false
     */
    public function getBasket(){
        $cookies = new TCookies();
        if(isset($cookies->busket)){
            $coo = json_decode(stripslashes($cookies->busket));
            $i=0;
            if(!empty($coo)){
                foreach ($coo as $c){
                    $items[$i] = $this->getItem($c->id);
                    $items[$i]->count = $c->count;
                    $items[$i]->sizes = implode(",", (array) $c->size);
                    $i++;
                }
                return $items;
            }
        }else{
            return false;
        }
    }
    private function clearBasket(){
        $cookies = new TCookies();
        unset($cookies->busket);
    }
    public function order(& $form){
        //Отображаем форму
        $form->beginForm("orderform","orderform","","post");
        $form->insertField("inputText","fio","ФИО","","fio",true);
        $form->insertField("email","email","E-mail","","email",true);
        $form->insertField("phone","phone","Мобильный телефон","","phone",true);
        $form->insertField("inputText","city","Населенный пункт","","city",true);
        $form->insertField("inputText","street","Улица","","street",true);
        $form->insertField("inputText","home","Дом, строение, квартира","","home",true);
        //скрипт формы
        $script="
            var params = d_get_post();
            $.getJSON('/ajax/catalog?action=addOrder',params).done(function( data ) {
                
            });
        ";
        //$form->addScript($script); 
    }
    private function addOrder(){
        //Проверяем есть ли в cookies заказы
        $items=$this->getBasket();
        $post = $this->post;
        if($items){
            //есть ли все поля
            if($post->fio && $post->email && $post->phone && $post->city && $post->street && $post->home){
                $hash = md5($post->email.$post->phone);
                //проверяем есть ли в бд
                $res = $this->template->db->select("SELECT * FROM catalog_buyers WHERE hash='".$hash."'")->count();
                $pass = $this->template->auth->generateHash(8);
                if(!$res){
                    //заносим в бд покупателя
                    $id_buyers = $this->template->db->insert('catalog_buyers',array(
                        "mail"=>$post->email,
                        "phone"=>$post->phone,
                        "pass"=>$pass,
                        "city"=>$post->city,
                        "street"=>$post->street,
                        "home"=>$post->home,
                        "name"=>$post->fio,
                        "hash"=>$hash
                        ));
                }else{
                    $res = $this->template->db->select("SELECT id FROM catalog_buyers WHERE hash='".$hash."'")->current();
                    $id_buyers = $res->id;
                    // заносим заказ
                    $id_order = $this->template->db->insert('catalog_orders',array(
                        "id_buyers"=>$id_buyers,
                        "status" => "pending",
                        "sum"=>$this->getSumItems($items)
                    ));
                    // заносим товары в заказ
                    foreach($items as $item){
                        $this->template->db->insert("catalog_order_tems",array(
                            "id_orders"=>$id_order,
                            "id_item"=>$item->id,
                            "price"=>$item->price_int,
                            "count"=>$item->count,
                            "value"=>$item->sizes
                        ));
                    }
                    // удаляем данные с корзины
                    $this->clearBasket();
                    // отправляем письма как админу так и пользователю
                    //ПИСЬМО ПОЛЬЗОВАТЕЛЮ
                    $mail = new TMail();
                    ob_start();
                    include(MODULES_DIR.$this->name."_".$this->version.DS."template".DS."mail_user.php");
                    $message = ob_get_contents();
                    ob_end_clean();
                    $mail->addRecipient($post->email);
                    $mail->setSubject("Ваш заказ принят. № ". $id_order);
                    $mailfrom = "mail@cathod.ru"; //здесь указывается адрес почты магазина
                    $mail->setSender($mailfrom);
                    $mail->setBody($message, true);
                    $mail->send();
                    
                    //ПИСЬМО АДМИНУ
                    ob_start();
                    include(MODULES_DIR.$this->name."_".$this->version.DS."template".DS."mail_admin.php");
                    $message = ob_get_contents();
                    ob_end_clean();
                    $mail->addRecipient("fred-mayer@list.ru"); // здесь e-mail админа
                    $mail->setSubject("Поступил новый заказ № ". $id_order);
                    $mailfrom = "mail@cathod.ru"; //здесь указывается адрес почты магазина
                    $mail->setSender($mailfrom);
                    $mail->setBody($message, true);
                    $mail->send();
                    
                    return true;
                }
            }
        }
    }
    public function getCatBreadcrumbs($catid){
        $db = $this->template->db;
        $par = $db->select("SELECT * FROM catalog_cats WHERE id=".$catid)->current();
        $this->breadcrumbs[] = $par;
        if($par->parentid>0){
            $this->getCatBreadcrumbs($par->parentid);
        }
    }
    public function route($router){ // поиск категории и товаров в строке URI
        $i=1;
        $res = false;
        while(isset($router[$i]) && $router[$i]){
            if(is_numeric($router[$i])){ //если число
                $res['idItem'] = $router[$i];
            }else{
                $res['idCats'][] = $router[$i];
            }
            $i++;
        }
        return $res;
    }
    public function getItems($catids,$limit=0,$order="i.sale, i.price ASC"){
	    $db = $this->template->db;
	    //Считываем товары
            //*** добавить разделы страниц
            $sql = "SELECT i.*,m.name as mag_name,m.trekking_url,m.logo 
            FROM catalog_items AS i LEFT JOIN catalog_magazine AS m ON i.id_mag=m.id 
            WHERE i.id_cat IN (".$catids.") AND i.hide='false' AND m.hide=1 ORDER BY i.sale, i.price ASC";
            if($limit>0){
                $sql .= " LIMIT ".$limit;
            }
            $res = $db->select($sql)->toObject();
            if(count($res)>0){
                for($i=0;$i<count($res);$i++){

                    //добавляем рубли к ценам
                    $currency = ($res[$i]->currencyid)? $res[$i]->currencyid:' <span class="currency">р</span>';
                    $res[$i]->price .= $currency;
                    $res[$i]->price_old .= $currency;

                    //переводим размеры в массив
                    $size = explode("]",str_replace("[", "", $res[$i]->size));
                    $res[$i]->size = $size;
                }
            }else{ $res=false; }
	    return $res;
    }
    public function getAdminToolbar( $attr )
    {
        $buttons[] = array('action'=>'addMagazine', 'icon'=>'shopping-cart', 'text'=>'', 'title'=>'Добавить магазин для парсинга товаров');
        $buttons[] = array('action'=>'magazineCats', 'icon'=>'tasks', 'text'=>'', 'title'=>'Страницы для парсинга');
        $buttons[] = array('action'=>'parse', 'icon'=>'refresh', 'text'=>'', 'title'=>'Обновить товары с магазинов');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}
?>