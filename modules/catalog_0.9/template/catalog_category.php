<?php
$data = $this->getData();
$items = $data['items'];
$catalog = $data['catalog'];
?>
<div class="catalog" idmodule="<? echo $data['idmodule'] ?>">
    <? echo $catalog->breadcrumbs ?>
    <div class="clearfix"></div>
    <hr/>
    <div class="row-fluid products">
    <?
    $row = 0;
    foreach($items as $item){
        $row++;
        if($row==5){ echo '</div><div class="row-fluid products">'; $row=1; }
    ?>
       <div class="span3">
           <div class="thumbnail product" idproduct="<? echo $item->id ?>">
               <!--<a class="logoMag" href="<? echo $item->trekking_url ?>" title="<? echo $item->mag_name ?>" rel="tooltip">
                   <img src="/media/images/<? echo $item->logo ?>" />
               </a>-->
           <a href="<? echo $item->item_url ?>">
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
           <div class="clearfix"></div>
<?php
            if ( $item->size !== null )
            {
?>
                <small>Размеры в наличии:
               <div class="sizes"><? 
               
                echo $item->size_str;
               ?></div>
               </small>

<?php
            }
?>
           </a>
           </div>
       </div>
        
    <?
    
    }
    echo $catalog->getPagination();
    /*
    $count_p = floor($catalog->pag_count/$catalog->limit);
    $url = $_SERVER["REQUEST_URI"];
    $purl = parse_url($url);
    $p = (isset($this->get->p))? $this->get->p->int():1;
    //print_r($items);
    ?>
        <div class="clearfix"></div>
<? if($count_p>1){ ?>
<div class="pagination">
    <ul>
    <li class="<? echo ($p==1)? "disabled":"" ?>"><a href="<? echo $purl['path']."?p=".($p-1) ?>">Назад</a></li>
    <? for($i=1;$i<=$count_p;$i++){ ?>
        <li class="<? echo ($p==$i)? "active":""; ?>"><a href="<? echo $purl['path']."?p=".$i ?>"><? echo $i ?></a></li>
    <? } ?>
    <li class="<? echo ($p>$count_p)? "disabled":"" ?>"><a href="<? echo $purl['path']."?p=".($p+1) ?>">Вперед</a></li>
    </ul>
</div>
<? } */ ?>
        <script>
                   $(document).ready(function(){
                       $(".logoMag").tooltip();
                   });
        </script>
    </div>
</div>
