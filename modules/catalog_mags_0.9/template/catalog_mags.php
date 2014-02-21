<?php
 $data = $this->getData();
 $alllink = $data['alllink'];
 $data = $data['mags'];
?>
<h4>Магазины</h4>
<div class="row-fluid select-pictures">
    <ul class="thumbnails">
        <?
        $i = 0;
        foreach($data as $item){
           if($i==2){
               echo '</ul><ul class="thumbnails">';
               $i=0;
           }
           $i++;
        ?>
            <li class="span6">
                <a class="thumbnail mag <? if (isset($this->get->mag)){ echo ($this->get->mag==$item->id)? "active":""; } ?>" href="<? echo $item->link ?>">
                    <img src="/media/logos/<? echo $item->logo ?>">
                </a>
            </li>
        <? } ?>
            <li class="span6">
                <a class="thumbnail" href="<? echo $alllink ?>">
                    <h5>ВСЕ МАГАЗИНЫ</h5>
                </a>
            </li>
    </ul>
</div>

