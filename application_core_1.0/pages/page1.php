
<?php

exit();

//Контроллер страницы page1


$template->setTitle( 'offer_form' );

$template->setStyle( 'flexslider.css' );

$template->setScript( 'jquery.flexslider.js' );
$template->setScript( 'banner.js' );
$template->setScript( 'ckeditor/ckeditor.js' );

//обращаемся к модулям которые нам нужны на странице назначая каждому модулю позицию
    $content = $template->getModule( 'news' ); // возвращает класс расширенного модуля

$offer_form = $template->getModule( 'offer_form' );

$banner = $template->getModule( 'banner' );

//$module2 = $module->getModule( 'module2' );

//можем совершить некоторые операции, подгрузить несколько модулей.
//$display = $module1->display();//выполняем операцию отображения модуля - весь html записывается в переменную

//$template->setPos('top','module1',$display); //записываем позицию

//$template->setPos( 'section', $banner );

//$template->setPos( 'section', $offer_form );

    //$template->setPos( 'section', $content );

//$template->setPos( 'article', $module2 );


/*$list = $template->db->select( 'SELECT c.name as cat, i.name, i.url FROM catalog_items i, catalog_cats c WHERE i.mag_id=1 AND i.catid=c.id' );
        
$fp = fopen( 'media/atlasformen.csv', 'w' );

foreach ( $list as $line )
{
    fputcsv( $fp, $line->toArray() );
}

fclose( $fp );

$list = $template->db->select( 'SELECT c.name as cat, i.name, i.url FROM catalog_items i, catalog_cats c WHERE i.mag_id=3 AND i.catid=c.id' );
        
$fp = fopen( 'media/trendsbreands.csv', 'w' );

foreach ( $list as $line )
{
    fputcsv( $fp, $line->toArray() );
}

fclose( $fp );
exit(); */
?>