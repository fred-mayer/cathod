<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->setTitle( 'Добавить статью' );
$form = new TForm();
$form->beginForm();
$params = $module->getParams();
$params=json_decode($params);
$form->hidden("idcat", $params->idcat);
$form->inputText("title","Заголовок");
$form->inputFile("img","Изображение");
$form->ckeditor( 'content' );
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
$this->displayDialog('attach');
?>

