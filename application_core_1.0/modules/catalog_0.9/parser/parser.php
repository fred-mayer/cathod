<?php

//error_reporting (E_ALL);
ini_set('display_errors', 1);
error_reporting( E_ERROR | E_NOTICE );
include_once( 'log.php' );
abstract class TParser_catalog
{
    protected $db;
    protected $log;

    public function __construct( $db, $cat )
    {
        $this->db = $db;
        $this->log = new log($db);
        $this->log->getStart();
        
        //$mag = $this->db->select( 'SELECT * FROM catalog_magazine WHERE id='.$idmag )->current();

        //$cats = $this->db->select( 'SELECT id_cat, url FROM catalog_mag_cats WHERE id_mag='.$mag->id );
        
        /*if($this->log->loadStep($idmag)){
            $curCat = $this->log->loadStep($idmag);
        }*/
	//foreach ( $cats as $cat )
        //{
            // Получаем контент
            /*if (!isset($curCat) || $curCat==$cat->id_cat){
                if(isset($curCat)) unset($curCat);*/

                $expCat = explode(";", $cat->url_cat);
                if( count($expCat) > 1){
                    foreach( $expCat as $c){
                        $this->foreach_page( $c, $cat );
                    }
                }else{
                    $this->foreach_page( $cat->url_cat, $cat );
                }
            //}
        //}
        // Чистим мусор, помечаем все необновленые товары как скрытые
        $date = new DateTime();
        $date->modify('-1 day');
        $this->db->query( 'UPDATE catalog_items SET hide=\'true\' WHERE id_mag='.$cat->id_mag.' 
                                AND id_cat='.$cat->id_cat.' 
                                AND date<\''.$date->format( 'Y-m-d H:i:s' ).'\'' );
    }

    protected function file_get_contents_curl( $url, $post='' )
    {
        $ch = curl_init();
        
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        
        if ( $post != '' )
        {
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );
        }
        
        $data = curl_exec( $ch );
        
        curl_close( $ch );
        
        return $data;
    }
    
    protected function foreach_page( $url, $cat )
    {
        var_dump( 'item_cat', $url, $cat->name );
        $this->log->saveStep($cat->id_mag,$cat->id_cat);
//$post = 'ctl00$smTheseus=ctl00$cphMain$ctl00$upShowProductsContainer|ctl00$cphMain$ctl00$btnPostback&__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.urlencode('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FgIfA2hkZAIQDw8WAh8DaGRkAhEPDxYCHwNoZGQCEg8PFgIfA2hkZAIUDw8WAh8DaGRkAhUPDxYCHwNoZGQCFg9kFgJmDxYCHwIFUA0KICAgICAgPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iL2pzL3J1dGFnLWFmbS1taW4uanMiPjwvc2NyaXB0Pg0KCQkJZBgBBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WEwVSY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkR2VuZGVyJGN0bDAwJGNoa0d1aWRlZEdlbmRlcgVSY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkR2VuZGVyJGN0bDAxJGNoa0d1aWRlZEdlbmRlcgVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDAkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDEkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDIkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDMkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDQkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDUkY2hrR3VpZGVkU2l6ZQVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwMCRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDEkY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDAyJGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwMyRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDQkY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDA1JGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwNiRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDckY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDA4JGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwOSRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMTAkY2hrR3VpZGVkQ29sb3Vy').'&ctl00$ctl02$txtSearch=Поиск по товарам или артикулам&ctl00$cphMain$ctl00$hidLoadProductsAfterPostback=&ctl00$cphMain$ctl00$hidPageNumber=1&ctl00$cphMain$ctl00$hidTotalItemsToShow=100&ctl00$cphMain$ctl00$hidTotalNumberOfPages=1&ctl00$cphMain$ctl00$hidPagingGondolaValue=&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidRunShowInitialFilterSections=false&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidGuidedFilters=Gender*M|&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidGuidedFiltersUsage=1&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedGender$ctl00$hidGenderValue=M&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedGender$ctl01$hidGenderValue=F&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl00$hidSizeValue=S&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl01$hidSizeValue=M&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl02$hidSizeValue=L&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl03$hidSizeValue=XL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl04$hidSizeValue=XXL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl05$hidSizeValue=XXXL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidSizes=&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValueFirstTime=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValueFirstTime=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValue=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValue=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValuePrev=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValuePrev=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl00$hidColourValue=БЕЖЕВЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl01$hidColourValue=БЕЛЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl02$hidColourValue=ЖЕЛТЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl03$hidColourValue=ЗЕЛЕНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl04$hidColourValue=КОРИЧНЕВЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl05$hidColourValue=КРАСНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl06$hidColourValue=СЕРЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl07$hidColourValue=СИНИЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl08$hidColourValue=упаковка&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl09$hidColourValue=ЧЕРНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl10$hidColourValue=ЭКРЮ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidColours:&ctl00$cphMain$ctl00$cmbPriceSort=0&ctl00$cphMain$ctl00$cmbProductsPerPage=100&ctl00$ctl04$txtFooterNewsEmail=Ваш email&ctl00$cphMain$ctl00$btnPostback=';
//var_dump($cat->post);
        // Получаем контент
        $dom = new DOMDocument();
        $source = mb_convert_encoding($this->file_get_contents_curl( $url, $cat->post ), 'HTML-ENTITIES', 'utf-8');
        $dom->loadHTML( $source );


        $this->foreach_item( $dom, $cat );

        return $dom;
    }
    
    protected function post( $url, $dom )
    {
        $post = '';
        $n = '';
        
        $inputs = $dom->getElementsByTagName( 'input' );
        foreach ( $inputs as $input )
        {
            if ( ($attr = $input->attributes->getNamedItem( 'type' )) !== null )
            {
                if ( $attr->value == 'hidden' )
                {
                    $post .= $n.$input->attributes->getNamedItem( 'name' )->value.'='.urlencode( $input->attributes->getNamedItem( 'value' )->value );
                    $n = '&';
                }
            }
        }
        $post = 'ctl00$smTheseus=ctl00$cphMain$ctl00$upShowProductsContainer|ctl00$cphMain$ctl00$btnPostback&__EVENTTARGET=&__EVENTARGUMENT=&__LASTFOCUS=&__VIEWSTATE='.urlencode('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FgIfA2hkZAIQDw8WAh8DaGRkAhEPDxYCHwNoZGQCEg8PFgIfA2hkZAIUDw8WAh8DaGRkAhUPDxYCHwNoZGQCFg9kFgJmDxYCHwIFUA0KICAgICAgPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iL2pzL3J1dGFnLWFmbS1taW4uanMiPjwvc2NyaXB0Pg0KCQkJZBgBBR5fX0NvbnRyb2xzUmVxdWlyZVBvc3RCYWNrS2V5X18WEwVSY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkR2VuZGVyJGN0bDAwJGNoa0d1aWRlZEdlbmRlcgVSY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkR2VuZGVyJGN0bDAxJGNoa0d1aWRlZEdlbmRlcgVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDAkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDEkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDIkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDMkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDQkY2hrR3VpZGVkU2l6ZQVPY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkU2l6ZXMkY3RsMDUkY2hrR3VpZGVkU2l6ZQVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwMCRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDEkY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDAyJGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwMyRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDQkY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDA1JGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwNiRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMDckY2hrR3VpZGVkQ29sb3VyBVNjdGwwMCRjcGhNYWluJGN0bDAwJG1vZHVsZUd1aWRlZE5hdkF0bGFzMTk4MCRyZXBHdWlkZWRDb2xvdXJzJGN0bDA4JGNoa0d1aWRlZENvbG91cgVTY3RsMDAkY3BoTWFpbiRjdGwwMCRtb2R1bGVHdWlkZWROYXZBdGxhczE5ODAkcmVwR3VpZGVkQ29sb3VycyRjdGwwOSRjaGtHdWlkZWRDb2xvdXIFU2N0bDAwJGNwaE1haW4kY3RsMDAkbW9kdWxlR3VpZGVkTmF2QXRsYXMxOTgwJHJlcEd1aWRlZENvbG91cnMkY3RsMTAkY2hrR3VpZGVkQ29sb3Vy').'&ctl00$ctl02$txtSearch=Поиск по товарам или артикулам&ctl00$cphMain$ctl00$hidLoadProductsAfterPostback=&ctl00$cphMain$ctl00$hidPageNumber=1&ctl00$cphMain$ctl00$hidTotalItemsToShow=100&ctl00$cphMain$ctl00$hidTotalNumberOfPages=1&ctl00$cphMain$ctl00$hidPagingGondolaValue=&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidRunShowInitialFilterSections=false&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidGuidedFilters=Gender*M|&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidGuidedFiltersUsage=1&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedGender$ctl00$hidGenderValue=M&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedGender$ctl01$hidGenderValue=F&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl00$hidSizeValue=S&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl01$hidSizeValue=M&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl02$hidSizeValue=L&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl03$hidSizeValue=XL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl04$hidSizeValue=XXL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedSizes$ctl05$hidSizeValue=XXXL&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidSizes=&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValueFirstTime=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValueFirstTime=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValue=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValue=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMinValuePrev=349&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidPriceMaxValuePrev=2029&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl00$hidColourValue=БЕЖЕВЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl01$hidColourValue=БЕЛЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl02$hidColourValue=ЖЕЛТЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl03$hidColourValue=ЗЕЛЕНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl04$hidColourValue=КОРИЧНЕВЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl05$hidColourValue=КРАСНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl06$hidColourValue=СЕРЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl07$hidColourValue=СИНИЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl08$hidColourValue=упаковка&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl09$hidColourValue=ЧЕРНЫЙ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$repGuidedColours$ctl10$hidColourValue=ЭКРЮ&ctl00$cphMain$ctl00$moduleGuidedNavAtlas1980$hidColours:&ctl00$cphMain$ctl00$cmbPriceSort=0&ctl00$cphMain$ctl00$cmbProductsPerPage=100&ctl00$ctl04$txtFooterNewsEmail=Ваш email&ctl00$cphMain$ctl00$btnPostback=';
//var_dump($post);
        $dom = new DOMDocument();
        $source = mb_convert_encoding($this->file_get_contents_curl( $url, $post ), 'HTML-ENTITIES', 'utf-8');
//var_dump($source);
//exit();
        $dom->loadHTML( $source );
        
        return $dom;
    }
    
    protected function item( $node, $cat )
    {
        $item['id_cat'] = $cat->id_cat;
        $item['id_mag'] = $cat->id_mag;
        
        $item['url'] = $this->url( $node, $cat->url_mag );
        $item['picture'] = $this->picture( $node, $cat->url_mag );
        $item['name'] = $this->db->real_escape_string( $this->name( $node ) );
        $item['price'] = $this->price( $node );
        if ($item['price']==""){
            echo "<br/>".$item['url']."<br>";
        }
        $item['price_old'] = $this->price_old( $node );
        $item['sale'] = $this->db->real_escape_string( $this->sale( $node ) );
        
        // переходим на страницу подробней (получаем все фото, размер, ...)
        $dom = new DOMDocument();
        $source = mb_convert_encoding($this->file_get_contents_curl( $item['url'] ), 'HTML-ENTITIES', 'utf-8');
        $dom->loadHTML( $source );

        $attr['size'] = $this->size( $dom );

        $item['description'] = $this->db->real_escape_string( $this->description( $dom ) );
        $item['articul'] = $this->db->real_escape_string( $this->articul( $dom ) );
        if($item['articul']===null)
            $item['articul'] = $this->db->real_escape_string( $this->articulByUrl( $item['url'] ) );

        $item['hash'] = md5( $item['id_mag'].$item['url'].$item['name'].$item['articul'] );
        
        $pictures = $this->pictures( $dom, $cat->url_mag );
        
        if ( $item['picture'] == '' ) $item['picture'] = $pictures[0];
        
        $params = array();
        if ( method_exists($this,"brand")) $params['brand'] = $this->brand($dom);

//var_dump( $item, $attr, $pictures );
//exit();
        if ( !($item['price'] == '' && $item['articul'] == '') )
            $this->insert( $item, $attr, $pictures,$params );
    }
    private function printt($item){
        echo "<li>".$item->name." ".$item['url']." ".$item['price']." ".$item['articul']."</li>";
    }
    private function insert( $item, $attr, $pictures,$params )
    {
        $this->log->saveItem($item);
        if ( ($id = $this->db->select( 'SELECT id FROM catalog_items WHERE hash=\''.$item['hash'].'\'' )->current('id')) !== false )
        {
            $this->db->update( 'catalog_items', array( 'price' => $item['price'], 
                                                       'price_old' => $item['price_old'], 
                                                       'sale' => $item['sale'], 
                                                       'hide' => 'false', 
                                                       'date' => date('Y-m-d H:i:s') ), 'id='.$id );

            $this->update_attr( $id, $attr );
            
            return $id;
        }
        else
        {
            if ( ($id = $this->db->insert( 'catalog_items', $item )) !== false )
            {
                $this->insert_attr( $id, $attr );
                $this->insert_pictures( $id, $pictures );
                if(isset($params['brand'])){
                    $this->insert_params( $id, $params );
                }
            }
            
            return $id;
        }
    }
    
    private function insert_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            if ( $value !== null || $value !== '' )
                $this->db->insert( 'catalog_attr', array('iditem'=>$id, 'field_name'=>$key, 'field_value'=>$value) );
        }
    }
    
    private function insert_pictures( $id, $pictures )
    {
        foreach ( $pictures as $picture )
        {
            $this->db->insert( 'catalog_pictures', array('iditem'=>$id, 'picture'=>$picture) );
        }
    }
    private function insert_params( $id, $item )
    {
            $this->db->insert( 'catalog_items_params', array('id_item'=>$id, 'field_name'=>'brand', 'field_value'=>$item['brand']) );

    }
    
    private function update_attr( $id, $attr )
    {
        foreach ( $attr as $key => $value )
        {
            $this->db->update( 'catalog_attr', array( 'field_value'=>$value ), 'iditem='.$id.' AND field_name=\''.$key.'\'' );
        }
    }
    
    abstract protected function foreach_item( $dom, $cat );

    abstract protected function url( $node, $url='' );

    abstract protected function picture( $node, $url='' );

    abstract protected function name( $node );

    abstract protected function price( $node );

    abstract protected function price_old( $node );

    abstract protected function sale( $node );

    abstract protected function size( $node );

    abstract protected function pictures( $node );

    abstract protected function description( $node );

    abstract protected function articul( $node );
    
    protected function getElement( $node, $tagName, $class='' )
    {
        //var_dump($tagName, $class);

        if ( ($elements = $node->getElementsByTagName( $tagName )) !== null )
        {
            foreach ( $elements as $element )
            {
                if ( $class == '' )
                    return $element;


                if ( ($attr = $element->attributes->getNamedItem( 'class' )) !== null )
                {
                    if ( $attr->value == $class )
                    {
                        return $element;
                    }
                }
            }
        }
        return null;
    }

    protected function getElementValue( $node, $tagName, $class='' )
    {
        if ( ($element = $this->getElement( $node, $tagName, $class )) !== null )
        {
            return $element->nodeValue;
        }
        return '';
    }

    protected function getAttributValue( $node, $tagName, $attr )
    {
        if ( ($elements = $node->getElementsByTagName( $tagName )) !== null )
        {
            foreach ( $elements as $element )
            {
                if ( ($a = $element->attributes->getNamedItem( $attr )) !== null )
                {
                    return $a->value;
                }
            }
        }
        return '';
    }
}

?>