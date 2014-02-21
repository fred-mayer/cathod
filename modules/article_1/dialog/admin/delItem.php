<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$item = $module->admin->getItem($template->get->id->int());
$this->setTitle( 'Удалить статью');
$form = new TForm();
$form->beginForm();
$form->hidden("id", $item->id);
$form->html("<p>Вы действительно хотите удалить статью \"".$item->title."\"?</p>");
$form->endForm();

$this->setBody( $form );
$this->setNameButtonPrimary( 'Удалить', $this->urlAction($template, '', true ) );
$this->displayDialog();
?>
