<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!--- наверное надо поставить резиновую шапку -->
<h2>Здравствуйте, <? echo $post->fio ?></h2>
<p>Спасибо, что выбрали наш интернет-магазин! В ближайшее время с Вами свяжутся операторы для уточнения и подтверждения параметров заказа, а также условий доставки и оплаты.</p>
<h3>Ваш заказ: № <? echo $id_order ?></h3>
<h3>Состояние: Поступил в обработку</h3>
<table width="100%" cellpadding="4" cellspacing="0">
    <tr>
        <th></th>
        <th>Описание</th>
        <th>Магазин</th>
        <th>Кол-во</th>
        <th>Цена</th>
        <?
        foreach($items as $item){
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
        <? } ?>
        <tr>
            <td colspan="4">
                <strong>Итого:</strong>
            </td>
            <td>
                <strong><? echo $this->getSumItems($items); ?> <span class="currency">р</span></strong>
            </td>
        </tr>
    </tr>
</table>
