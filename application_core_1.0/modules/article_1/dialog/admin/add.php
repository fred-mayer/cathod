<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->setTitle( 'Добавить статью' );
$form = new TForm(array("img_readmore"=>'show'));
$form->beginForm();
$params = $module->getParams();
$params=json_decode($params);
$form->hidden("idcat", $params->idcat);
$form->inputText("title","Заголовок");
$form->inputFile("img","Изображение");
$form->ckeditor( 'content' );
$form->inputText("url","Ссылка на подробнее");
$form->checkbox("img_readmore","Показать изображение в подробном виде статьи","show");
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
$this->displayDialog('attach');
?>

