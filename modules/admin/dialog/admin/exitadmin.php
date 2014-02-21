<?php
	$this->setTitle( 'Выйти?' );
	$form = new TForm();
    $form->beginForm();
    $form->endForm();
    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Выйти', $this->urlAction( $template ) );
    $this->displayDialog();
?>