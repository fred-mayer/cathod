<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$data = $this->getData();
echo '<ul class="breadcrumb">';
foreach($data as $br){
?>
    <li><a href="<? echo $br['link'] ?>"><? echo $br['title'] ?></a> <span class="divider">/</span></li>
<? }
echo '</ul>';
?>
