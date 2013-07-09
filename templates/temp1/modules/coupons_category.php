<?php

    $data = $this->getData();

?>
    <h4>Категория</h4>
<?php
    if ( count($data) > 0)
    {
?>
    <ul class="nav nav-list">
<?php
        foreach ( $data as $item )
        {
?>
        <li><a href="/coupons?coupon_category=<?php echo $item->id; ?>"><?php echo $item->title; ?></a></li>
<?php
        }
?>
    </ul>
<?php
    }
?>
