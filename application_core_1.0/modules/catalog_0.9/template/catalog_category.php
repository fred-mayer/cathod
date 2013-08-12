<?php
$data = $this->getData();
$cat = $data['cat'];
$bc = $data['breadcrumbs'];
$items = $data['items'];
?>
<div class="catalog" idmodule="<? echo $data['idmodule'] ?>">
    <ul class="breadcrumbs unstyled">
        <li><a href="/catalog/">Магазин</a></li><li>></li>
        <?
        $uri = "/catalog/";
        for($i=0;$i<count($bc)-1;$i++){
            $uri .=$bc[$i]->alias."/";
            ?><li><a href="<? echo $uri ?>"><? echo $bc[$i]->name ?></a></li><li>></li><?
        }
        ?>
        <li><h3><? echo $cat->name ?></h3></li>
    </ul>
    <div class="clearfix"></div>
    <hr/>
    <div class="row products">
    <?
    foreach($items as $item){
    ?>
       <div class="span2">
           <div class="thumbnail product" idproduct="<? echo $item->id ?>">
               <a class="logoMag" href="<? echo $item->trekking_url ?>" title="<? echo $item->mag_name ?>" rel="tooltip">
                   <img src="/media/images/<? echo $item->logo ?>" />
               </a>
           <a href="<? echo $item->url ?>">
           <div class="imgblock"><img src="<? echo $item->picture ?>" /></div>
            <div class="title"><? echo $item->name ?></div>
           <div class="params">
               <? if($item->sale){ ?><div class="sale"><? echo $item->sale ?>%</div><? } ?>
               <div class="price"><? echo $item->price ?>
                   <? if($item->price_old>0){ ?>
                   <span class="oldprice"><? echo $item->price_old ?></span>
                   <? } ?>
               </div>
               <div class="clearfix"></div>
           </div>
                <small>Размеры в наличии:
               <div class="sizes"><? 
               $ss="";
                foreach($item->size as $size){ //***костыль разобраться с objectom
                    $ss .= ($size)? $size . ",":"";
                    
                }
                $ss = substr($ss,0,-1);
                echo ($ss)? $ss:"&nbsp;";
               ?></div>
               </small>
           </a>
           </div>
       </div>
    <?
    }
    ?>
        <script>
                   $(document).ready(function(){
                       $(".logoMag").tooltip();
                   });
        </script>
    </div>
</div>
