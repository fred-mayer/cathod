<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h2>Здравствуйте, поступил заказ № <? echo $id_order ?> от <? echo $post->fio ?></h2>
<p>E-mail: <? echo $post->email ?></p>
<p>Адрес: <? echo $post->city ?>, <? echo $post->street ?>, <? echo $post->home ?></p>
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
