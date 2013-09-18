<?php

$this->setTitle( 'Добавить пункт меню');
$form = new TForm();
$form->beginForm();
$params = $module->getParams();
$params=json_decode($params);
$form->inputText("title","Заголовок");
$form->inputText("href","Адрес ссылки");
$form->hidden("name_group", $params->name_group);

$pages = $module->admin->getPages();
$options = array();
$i=0;
$options[$i]['value'] = 0;
$options[$i]['title'] = "Выберите страницу...";
foreach($pages as $m){
    $i++;
    $options[$i]['value'] = $m->id;
    $options[$i]['title'] = $m->title;
}
$form->select("id_page","На страницу",$options);

$menuItems = $module->admin->getMenu($params->name_group);
if($menuItems){
    $options = array();
    $i=0;
    $options[$i]['value'] = "-1";
    $options[$i]['title'] = "Выбрать...";
    foreach($menuItems as $m){
        $i++;
        $options[$i]['value'] = $m->id;
        $options[$i]['title'] = $m->title;
    }
    $form->select("id_parent","Родительский элемент",$options);
}
if($menuItems){
    $options = array();
    $i=0;
    $options[$i]['value'] = count($menuItems);
    $options[$i]['title'] = "Последний...";
    foreach($menuItems as $m){
        $i++;
        $options[$i]['value'] = $m->order;
        $options[$i]['title'] = $m->title;
    }
    $form->select("order","Порядок",$options);
}
$form->inputText("sfx","Класс меню");
$form->checkbox("separator","Пункт меню - разделитель",1);
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
$this->displayDialog();
?>
<script>
    function d_complete(){
        location.reload(); //пока можем только перезагрузить страницу(
    }
</script>
    
