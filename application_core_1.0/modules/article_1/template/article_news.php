<?php

    $data = $this->getData();
    $items = $data->getItems();
        
    if($items===false){
        echo $data->template->displaySystemMes("В данной категории нет статей!<br/> Для добавления статьи в категорию нажмите <i class='icon-plus'></i> на панели управления.");
    }else{ ?>
    <div class="news well">
    <ul class="news-nav">
    	<li class="nav-header">Новости</li>
        <?
        foreach($items as $item){
        ?>
        <li>
            <? 
                if($data->template->auth->isAuthorized){
                    $more = array('id'=>$item->id,'idmodule'=>$data->module->idmodule);
                    echo $data->template->getAdminToolbar($data->module, $more,false,'getElementAdminToolbar');
                }
            ?>
            <span class="date blue-decoration"><? $curdate = new TString($item->date); echo $curdate->convertDate(); ?></span>
            <p><? echo strip_tags($item->introtext) ?></p>
        </li>
        <?
        }
        ?>
    </ul>
    </div>
    <? } ?>
