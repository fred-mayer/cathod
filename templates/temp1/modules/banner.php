<?php

    $data = $this->getData();

?>
<div class="banner<?php if ( $template->auth->isAdmin ) echo ' admin-module'; if ( $this->hide == 'hide' ) echo ' unpublished'; ?>" 
     <?php if ( $template->auth->isAdmin ) echo 'idbanner="'.$this->idmodule.'" idpage="'.$template->idpage.'" idmodule="'.$this->idmodule.'" set_pos="'.$this->set_pos.'"'; ?>>
    <div class="flexslider">
        <ul class="slides">
<?php

    foreach ( $data as $row )
    {
?>
            <li>
                <a href="<?php echo $row->href; ?>"><img src="/media/banner/banner_<?php echo $row->id.'.'.$row->src; ?>" /></a>
            </li>
<?php
    }
?>
        </ul>
    </div>
</div>
