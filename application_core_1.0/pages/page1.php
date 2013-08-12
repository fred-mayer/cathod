<?php

//set_time_limit( 1200 );

include_once( APPLICATION_CORE.'/model/offer.php' );



$offer = new TOffer();

//$category = $offer->getOffer( 1000349, 143, 'id DESC' )->toObject();
$offer_item = $offer->getOfferById( 40370112 );

var_dump( $offer_item );

$advertiser = $offer->getAdvertiser( $offer_item );

var_dump( $advertiser );


$category = $offer->getOfferById( 40886846 );

var_dump( $category );

$advertiser = $offer->getAdvertiserById( $category->idadvertiser );

var_dump( $advertiser );


$category = $offer->getOfferById( 38181451 );

var_dump( $category );

$advertiser = $offer->getAdvertiserById( $category->idadvertiser );

var_dump( $advertiser );
exit();

/*
$xml_parser = new TXML_parser();

$xml_parser->handler = function( $tag, $attr, $data, $parent )
{
    var_dump( '-------------' );
    var_dump($tag, $attr, $data);
    var_dump($parent);
};

//$xml_parser->parse("<A ID='hallo'>PHP</A>");

//$xml_parser->parse("<Ab ID='hallo2'");
//$xml_parser->parse(">PHP2</Ab>");

$xml_parser->parse("<Ab ID='hallo2'");
$xml_parser->parse("><A ID='hallo'>PHP</A><b ID='123'>js</b></Ab>");
*/


$xml_tag = new Ttag();

//$xml_tag->parseFile( '/Users/mizko/Documents/www/application_core_1.0/pages/4633-1.xml' );
$xml_tag->parseFile( '/Users/mizko/Documents/www/application_core_1.0/pages/dn.xml' );


var_dump( $xml_tag->count_offer );


/*$id = 1001390;

$cat = $xml_tag->category[$id];

$cat_res[] = $cat['name'];
echo $cat['name'].' -- ';

while ( $cat['parentId'] > 0 )
{
    $cat = $xml_tag->category[ $cat['parentId'] ];
    echo $cat['name'].' -- ';
    
    array_unshift( $cat_res, $cat['name'] );
}

var_dump( $cat_res );
*/
//$xml_tag->parse("<Ab ID='hallo2'");
//$xml_tag->parse("><A ID='hallo'>PHP</A><b ID='123'>js</b></Ab>");



exit();

?>