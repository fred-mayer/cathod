<?php

/**
 * @author Fred Mayer <mail@site-don.ru>
 * @copyright (c) 2013, Fred Mayer
 * 
 */
set_time_limit( 14400 );
$catalog = $template->getModule( 'catalog' );
$catalog->route = new module_catalog_route($template->get,$catalog);

$items = $template->db->select("SELECT i.id,i.name,i.link,m.name as mag_name,c.name as cat_name,i.price,i.picture,i.description FROM catalog_items AS i LEFT JOIN catalog_cats AS c ON i.id_cat=c.id LEFT JOIN catalog_magazine AS m ON i.id_mag=m.id WHERE i.hide='false' AND i.price!='' ORDER BY i.id_cat,i.sale ASC")->toObject();

$i=0;
foreach($items as $item){
    $offer[$i]['name'] = $item->name;
    $offer[$i]['mag_name'] = $item->mag_name;
    $offer[$i]['cat_name'] = $item->cat_name;
    if(empty($item->link)){
        $link = $catalog->route->getLinkItem($item->id);
        $offer[$i]['url'] = "http://newwa.ru".$link;
        $template->db->update("catalog_items",array("link"=>$link),"id=".$item->id);
    }else{
        $offer[$i]['url'] = "http://newwa.ru".$item->link;
    }
    $offer[$i]['price'] = $item->price;
    $offer[$i]['picture'] = $item->picture;
    $offer[$i]['description'] = $item->description;
    $i++;
}
$csv = new toCSV($offer);

echo $_SERVER['DOCUMENT_ROOT'];
class toCSV
{
    function __construct($offers){
        $fp = fopen('/home/user/data/www/dev1.cathod.ru/media/items.csv', 'w');

        foreach ($offers as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fclose($fp);
    }
}

exit();
?>
