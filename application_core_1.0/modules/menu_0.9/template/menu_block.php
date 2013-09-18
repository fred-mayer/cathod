<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
$items = $data->getMenuItems();
?>
<ul class="nav nav-list">
<? foreach($items as $item){ 
    if($item->separator=="yes"){
        ?><li class="nav-header <? echo $item->sfx ?>"><? echo $item->title ?></li><?
    }else{
    //ссылка
    $link = ($item->alias!="default")? DS.$item->alias:DS;//ссылка на страницу
    if($item->href) $link = DS.$item->href;//переназначаем на ручную
?>
    <li class=" <? echo $item->sfx ?>"><a href="<? echo $link ?>"><? echo $item->title ?></a></li>
<? 
    }
    //ищем подпункты
} ?>
