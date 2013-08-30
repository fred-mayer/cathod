<?php

/*
 * To change this template, choose Tools | Templates
 * 
 */
$data = $this->getData();
$items = $data['items'];
$module = $data['module'];
?>
<h1>Корзина</h1>
<table class="table table-striped table-bordered">
    <tr>
        <th style="width:70%">Описание товара</th>
        <th>Цена</th>
    </tr>
    <? if(isset($items[0]->id)):
        foreach($items as $item){
    ?>
    <tr id="<? echo $item->id ?>">
        <td>
            <div class="row-fluid">
                <div class="span3">
                    <a href="<? echo $item->item_url ?>"><img src="<? echo $item->picture ?>"></a>
                </div>
                <div class="span9">
                    <h5><? echo $item->name ?></h5>
                    <div class="row-fluid">
                        <div class="span3"><strong>Количество</strong></div>
                        <div class="span7">
                            <div class="input-append input-prepend" rel="<? echo $item->id ?>">
                                <button type="button" class="btn btn-mini minus" >-</button>
                                <input type="text" class="span2 count" value="<? echo $item->count ?>">
                                <button type="button" class="btn btn-mini plus" >+</button>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3"><strong>Размеры</strong></div>
                        <div class="span7"><? echo $item->sizes ?></div>
                    </div>
                </div>
            </div>
        </td>
        <td style="vertical-align: middle;">
            <div class="row-fluid">
                <div class="span8">
                    <strong class="price"><? echo $item->price ?></strong>
                    <p class="muted"><small>Скидка - <? echo $item->price_old_int - $item->price_int ?> <span class="currency">р</span></small></p>
                </div>
                <div class="span4" style="text-align: center;">
                    <a class="delItem" rel="<? echo $item->id ?>" href="javascript:void(0)">Удалить</a>
                </div>
            </div>
        </td>
    </tr>
    <? } ?>
    <tr>
        <td colspan="2">
            <div class="pull-right"><a href="/catalog/order/" class="btn">Оформить заказ</a></div>
            <h3>Сумма заказа: <span class="text-error"><span id="sum"><? echo $module->getSumItems($items); ?></span> <span class="currency">р</span></span></h3>
        </td>
    <tr>
   <? else: ?>
    <tr>
        <td colspan="2"><h1>Товаров в корзине нет</h1></td>
    </tr>
    <? endif; ?>
</table>
<script>
    $(".delItem").click(function(){
        var id = $(this).attr('rel');
        $.getJSON("/ajax/catalog?action=delBasket&id="+id,function(data){
            if(data['result']=='ok'){
                $('tr[id='+id+']').remove();
                $('#sum').text(data['sum']);
                $('.basket_module').updateBasket();
            }
        });
    });
    $(".plus").click(function(){
        var id = $(this).parent('.input-append').attr('rel');
        var curval = $(this).prev().val();
        curval++;
        $(this).prev().val(curval);
        $.getJSON("/ajax/catalog?action=updateBasket&id="+id+"&count="+curval,function(data){
            $('#sum').text(data['sum']);
            $('.basket_module').updateBasket();
        });
    });
    $(".minus").click(function(){
        var id = $(this).parent('.input-append').attr('rel');
        var curval = $(this).next().val();
        curval--;
        if(curval>0){
            $(this).next().val(curval);
            $.getJSON("/ajax/catalog?action=updateBasket&id="+id+"&count="+curval,function(data){
                $('#sum').text(data['sum']);
                $('.basket_module').updateBasket();
            });
        }
    });
</script>