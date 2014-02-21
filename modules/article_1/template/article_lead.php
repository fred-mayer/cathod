<?php
    /*
     * Шаблон для главной странице
     * 
     */
    $data = $this->getData();
    $items = $data->getItems();
    $span = 12/$data->cols;
        
    if($items===false){
        echo $data->template->displaySystemMes("В данной категории нет статей!<br/> Для добавления статьи в категорию нажмите <i class='icon-plus'></i> на панели управления.");
    }else{ ?>
    <div class="row-fluid">
    <ul class="thumbnails">
        <?
        $c=0;
        foreach($items as $item){
            if($c==$data->cols){
                echo '</ul></div><div class="row-fluid"><ul class="thumbnails">';
                $c=0;
            }
        ?>
        <li class="span<? echo $span ?>">
            <? 
                if($data->template->auth->isAuthorized){
                    $more = array('id'=>$item->id,'idmodule'=>$data->module->idmodule);
                    echo $data->template->getAdminToolbar($data->module, $more,false,'getElementAdminToolbar');
                }
            ?>
            <div class="thumbnail">
                <div class="img">
                <? if(!empty($item->image)){ ?><a href="<? echo ($item->url_readmore)? $item->url_readmore:$data->route->getRouteArticle($item->alias); ?>"><img src="/<? echo $item->image ?>" alt="<? echo $item->title ?>"></a><? } ?>
                </div>
                <div class="caption"><a href="<? echo ($item->url_readmore)? $item->url_readmore:$data->route->getRouteArticle($item->alias); ?>"><strong><? echo $item->title ?></strong></a></div>
                
            </div>
        </li>
        <?
            $c++;
        }
        ?>
    </ul>
    </div>
    <? } ?>
