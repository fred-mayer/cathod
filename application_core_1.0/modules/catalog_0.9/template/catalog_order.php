<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
$items = $data['items'];
$module = $data['module'];
$form = $data['form'];

?>
<h1>Оформление заказа</h1>
<?
//Все это делается для того что бы кнопка была после списка купленных товаров
ob_start();
$items = $module->getBasket();
?><table class="table table-condensed table-bordered">
    <tr>
        <th></th>
        <th>Описание</th>
        <th>Магазин</th>
        <th>Кол-во</th>
        <th>Цена</th>
    </tr>
<?
foreach ($items as $item){
    ?>
        <tr>
            <td style="width: 60px;">
                <img src="<? echo $item->picture ?>" style="width: 60px;">
            </td>
            <td>
                <strong><? echo $item->name ?></strong>
                <p><strong>Размеры:</strong> <? echo $item->sizes ?></p>
                <?
                /*
                 * Выводим артикул и выбранные аттрибуты
                 * echo $tiem->sku
                 * $module->print_attrs($item);
                 * 
                 */
                ?>
            </td>
            <td>
                <a href="<? echo $item->trekking_url ?>"><img src="/media/images/<? echo $item->logo ?>"><? echo $item->mag_name ?></a>
            </td>
            <td>
                <? echo $item->count ?>
            </td>
            <td>
                <? echo $item->price ?>
            </td>
        </tr>
    <?
}
?>
        <tr>
            <td colspan="4">
                <strong>Итого:</strong>
            </td>
            <td>
                <strong><? echo $module->getSumItems($items); ?> <span class="currency">р</span></strong>
            </td>
        </tr>
</table>
<div class="control-group">
        <div class="controls" style="text-align: right;">
            <button class="btn btn-primary btn-large" type="submit">Подтвердить и заказать</button>
        </div>
</div>
<? 
$html = ob_get_contents();
ob_end_clean();
$form->html($html);
$form->endForm();
echo $form;
        
?>

