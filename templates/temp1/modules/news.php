<?php

    $data = $this->getData();
    //var_dump($data);
    
    if ( isset($data['news']) )
    {
?>
<div class="news<?php if ( $template->auth->isAdmin ) echo ' admin-module'; if ( $this->hide == 'hide' ) echo ' unpublished'; ?>" 
     <?php if ( $template->auth->isAdmin ) echo ' idpage="'.$template->idpage.'" idmodule="'.$this->idmodule.'" set_pos="'.$this->set_pos.'"'; ?>>
    <h4>Новости</h4>
<?php
        foreach ( $data['news'] as $item )
        {
?>
    <article>
        <h1><a href="/news/<?php echo $item->id; ?>"><?php echo $item->title; ?></a></h1>
<?php
            if ( !(isset($this->params['img']) && $this->params['img'] === 'hide') )
            {
?>
        <a href="/news/<?php echo $item->id; ?>"><img src="<?php echo $item->img; ?>"></a>
<?php
            }
?>
        <p><?php echo $item->description; ?></p>
    </article>
<?php
        }
?>
</div>
<?php
    }


    if ( isset($data['new']) )
    {
?>
<div class="news<?php if ( $template->auth->isAdmin ) echo ' admin-module'; if ( $this->hide == 'hide' ) echo ' unpublished'; ?>" 
     <?php if ( $template->auth->isAdmin ) echo ' idpage="'.$template->idpage.'" idmodule="'.$this->idmodule.'" set_pos="'.$this->set_pos.'"'; ?>>
    <article>
        <h1><a href="/news/<?php echo $data['new']->id; ?>"><?php echo $data['new']->title; ?></a></h1>
        <?php echo $data['new']->text; ?>
    </article>
</div>
<?php
    }

?>