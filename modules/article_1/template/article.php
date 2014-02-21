<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
$item = $data->item;
?>
<article class="article">
    <? if($data->template->auth->isAuthorized){
                    $more = array('id'=>$item->id,'idmodule'=>$data->module->idmodule);
                    echo $data->template->getAdminToolbar($data->module, $more,false,'getElementAdminToolbar');
                }
    ?>
<h2><? echo $item->title ?></h2>
<? if(!empty($item->image) && $item->img_readmore=="show"){ ?><img class="media-object pull-left" src="/<? echo $item->image ?>" alt="<? echo $item->title ?>"><? } ?>
<?
    echo $item->introtext;
?>
<div class="clearfix"></div>
</article>
