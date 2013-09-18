<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$item = $module->admin->getItem($template->get->id->int());
$this->setTitle( 'Редактировать статью');
$form = new TForm(array("img_readmore"=>$item->img_readmore));
$form->beginForm();
$params = $module->getParams();
$params=json_decode($params);
$form->hidden("idcat", $params->idcat);
$form->hidden("id", $item->id);
$form->inputText("title","Заголовок", $item->title);
if(!empty($item->image)){
    $form->html('<div class="row-fluid">
            <div class="span3"><img src="'.$item->image.'" /></div>
        </div>
    ');
    $form->checkbox("delImg","Удалить изображение",1);
    $form->inputFile("img","или загрузить новое");
}else{
    $form->inputFile("img","Изображение");
}
$form->ckeditor( 'content', '', $item->introtext ); //отредактировать интротекст!!
$form->inputText("url","Ссылка на подробнее",$item->url_readmore);
$form->checkbox("img_readmore","Показать изображение в подробном виде статьи","show");
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
$this->displayDialog('attach');
?>
