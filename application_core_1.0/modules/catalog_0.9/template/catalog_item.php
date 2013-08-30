<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
$bc = $data['breadcrumbs'];
$item = $data['item'];
$module = $data['module'];
?>
<div class="catalog product_page" idmodule="<? echo $data['idmodule'] ?>">
    <ul class="breadcrumbs unstyled">
        <li><a href="/catalog/">Магазин</a></li><li>></li>
        <?
        $uri = "/".$module->getName()."/";
        for($i=0;$i<count($bc);$i++){
            $uri .=$bc[$i]->alias."/";
            ?><li><a href="<? echo $uri ?>"><? echo $bc[$i]->name ?></a></li><li>></li><?
        }
        ?>
        <li><h3><? echo $item->name ?></h3></li>
    </ul>
    <div class="clearfix"></div>
    <hr/>
    <div class="row-fluid">
        <div class="span6">
            <img class="main_image" src="<? echo $item->images[0] ?>" />
            <div class="row-fluid images-block">
                <? if (!empty($item->images)){
                    $i=0;
                   foreach($item->images as $image){
                ?>
                <div class="span3">
                    <img class="<? if($i==0){ ?>active<? } ?>" src="<? echo $image ?>" />
                </div>
                <? 
                        $i=1;
                   }
                        } ?>
            </div>
            <script>
                $(".images-block img").click(function(){
                    var attr = $(this).attr("src");
                    $("img.main_image").attr("src",attr);
                    $(".images-block img").removeClass("active");
                    $(this).addClass("active");
                });
            </script>
        </div>
        <div class="span6">
            <h1><? echo $item->name ?></h1>
            <div class="row-fluid">
                <div class="span6">
                    <div class="price"><? echo $item->price ?><span class="oldprice"><? echo $item->price_old ?></span></div>
                </div>
                <div class="span6">
                    <div class="sale pull-right"><? echo $item->sale ?>%</div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <strong>Артикул: </strong><? echo $item->articul ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 muted">
                    <? echo $item->description ?>
                </div>
            </div>
            <? 
            $module->printAttrsSelect($item->attrs);
            $module->printBasketButton($item->id); ?>
        </div>
    </div>
</div>
<? echo $module->printScriptBasket(); ?>
