<?php

    $data = $this->getData();
?>
<div class="catalog<?php if ( $template->auth->isAuthorized ) echo ' admin-module'; ?>" idmodule="<? echo $data['idmodule'] ?>">
    <?php //проверка заполненности всех полей для отображения модуля или магазина
    if(!isset($data['magazines'])): ?>
    <p class="text-error">Добавьте хоть один магазин для импорта товаров!</p>
    <?php endif; ?>
</div>
