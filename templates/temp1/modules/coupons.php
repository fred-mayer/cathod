<?php

    $data = $this->getData();
    //var_dump($data);

?>
    <h4>Купоны</h4>
<?php
    if ( count($data) > 0)
    {
        foreach ( $data as $item )
        {
?>
    <article>
        <h1><?php echo $item->title; ?></h1>
<?php
            if ( $item->img !== '' )
            {
?>
        <img src="<?php echo '/media/coupon/'.$item->img; ?>">
<?php
            }
?>
        <p>Срок действия: <?php echo $item->date_from; ?> - <?php echo $item->date_to; ?></p>
        <p><?php echo $item->desc; ?></p>
<?php
            if ( $item->promocode !== '' )
            {
?>
        <p>Промокод: <?php echo $item->promocode; ?></p>
<?php
            }
?>
    </article>
<?php
        }
    }
    else
    {
?>
    <p>Нет купонов</p>
<?php
    }
?>
