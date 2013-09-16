<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
$item = $data->item;
?>
<article class="article">
<h2><? echo $item->title ?></h2>
<? if(!empty($item->image)){ ?><img class="media-object pull-left" src="/<? echo $item->image ?>" alt="<? echo $item->title ?>"><? } ?>
<?
    echo $item->introtext;
?>
<div class="clearfix"></div>
</article>
