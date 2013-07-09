<? 
    $data = $this->getData();

print_r($data); ?>
<a href="<? echo $data['href'] ?>"><? echo $data['link'] ?></a>
<?php echo '<br>template - '.$template->getName();?>
<?
if ( $template->issetPos('article') )
{ ?>
    <section>
<? $template->getPos('article'); ?>
    </section>
<?
}