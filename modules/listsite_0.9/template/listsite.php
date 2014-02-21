<?php

    $data = $this->getData();
    
    
    if ( isset($data['current']) )
    {
        $item = $data['current'];
?>
        <div class="span10 thumbnail">
<?php
        if ( $this->auth->isAdmin && !$template->isPreview )
        {
?>
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a class="btn btn-mini" module="listsite" action="edit" idmodule="<?php echo $item->id; ?>" href="#"><i class="icon-pencil"></i></a>
                    <a class="btn btn-mini" module="listsite" action="del" idmodule="<?php echo $item->id; ?>" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
<?php
        }

?>
            <a href="<?php echo $item->url; ?>">
                <div class="img">
                    <img src="/media/logos/<?php echo $item->logo; ?>" alt="<?php echo $item->site; ?>">
                </div>
                <p><?php echo $item->site; ?></p>
            </a>
            <p class="head"><?php echo $item->descripion; ?></p> 
            <a href="<?php echo $item->url; ?>" class="btn btn-danger pull-right"><i class="icon-white icon-shopping-cart"></i> Перейти</a>
        </div>
<?php
    }
    else
    {
        $data = $data['list'];

        if ( count($data) > 0 )
        {
?>
        <ul class="thumbnails">
<?php

            foreach ( $data as $item )
            {
?>
            <li class="span5 thumbnail">
<?php

                if ( $this->auth->isAdmin && !$template->isPreview )
                {
?>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <a class="btn btn-mini" module="listsite" action="edit" idmodule="<?php echo $item->id; ?>" href="#"><i class="icon-pencil"></i></a>
                        <a class="btn btn-mini" module="listsite" action="del" idmodule="<?php echo $item->id; ?>" href="#"><i class="icon-remove"></i></a>
                    </div>
                </div>
<?php
                }

?>
                <a href="<?php echo $item->url; ?>">
                    <div class="img">
                        <img src="/media/logos/<?php echo $item->logo; ?>" alt="<?php echo $item->site; ?>">
                    </div>
                    <p><?php echo $item->site; ?></p>
                </a>
                <p class="head"><?php echo $item->descripion; ?></p> 
                <a href="<?php echo SERVER_NAME.'/listsite/'.$item->id; ?>" class="btn btn-danger pull-right"> Подробней</a>
                <a href="<?php echo $item->url; ?>" class="btn btn-danger pull-right"><i class="icon-white icon-shopping-cart"></i> Перейти</a>
            </li>
<?php
            }

?>
        </ul>
<?php
        }
    }

?>