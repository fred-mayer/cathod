<?php

/**
 * @author Fred Mayer <mail@site-don.ru>
 * @copyright (c) 2013, Fred Mayer
 * 
 */
$mags = $module->admin->getListMags();
$this->setTitle( 'Удалить товары магазина:' );

$form = new TForm();
$form->beginForm();
$form->select( 'magid',"Очистить товары магазина:",$mags );
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template,'',true ) );
$this->displayDialog();
?>
