<?php

set_time_limit( 600 );

define( 'CLASS_DIR', 'application_core_1.0/class/' );

include_once( CLASS_DIR.'mysql.php' );
include_once( CLASS_DIR.'simple_html_dom.php' );

$db = new TMySQL();


    function parser( $params, $db )
    {
        $parser = new TParserNews( $params );
        $parser->exec( $db );
    }

    $parser_new = $db->select( 'SELECT * FROM parser_new WHERE parser=\'on\'' );
    foreach ( $parser_new as $params )
    {
        $this->parser( $params, $db );
    }


class TParserNews
{
    protected $html;
    protected $params;

    public function __construct( $params )
    {
        $this->params = $params;
        
        $this->html = new simple_html_dom();
        $this->html->load_file( $this->params['site'] );
    }
    
    protected function getUrl( $href, $current_url )
    {
        $url = parse_url( $current_url );

        if ( preg_match( '/^(http:\/\/|https:\/\/|ftp:\/\/)/', $href ) == 1 )
            return $href;
        elseif ( preg_match( '/^(http:\/\/|https:\/\/|ftp:\/\/)'.$url['host'].'/', $href ) == 1 )
            return $href;
        elseif ( preg_match( '/^\//', $href ) == 1 )
            return $url['scheme'].'://'.$url['host'].$href;
        else
            return $current_url.$href;
    }
    
    protected function parserNew( $html )
    {
        $item = new simple_html_dom();
        $item->load( $html->innertext );

        $i = $item->find( $this->params['title'] );
        $data['title'] = trim( $i[0]->innertext );

        $i = $item->find( $this->params['link'] );
        $data['link'] = $this->getUrl( $i[0]->getAttribute( 'href' ), $this->params['site'] );

        $i = $item->find( $this->params['img'] );
        $data['img'] = $this->getUrl( $i[0]->getAttribute( 'src' ), $this->params['site'] );

        $i = $item->find( $this->params['description'] );
        $data['description'] = trim( $i[0]->innertext );


        $data['text'] = $this->parserNewText( $data['link'] );

        //var_dump($data);
        return $data;
    }
    
    protected function parserNewText( $link )
    {
        $item = new simple_html_dom();
        $item->load_file( $link );

        $i = $item->find( $this->params['text'] );
        return $this->optimizeText( $i[0], $link );
    }
    
    protected function optimizeText( $html, $link )
    {
        $item = new simple_html_dom();
        $item->load( $html->innertext );


        $img = $item->find( 'img' );
        foreach ( $img as $i )
        {
            $i->setAttribute( 'src', $this->getUrl( $i->getAttribute( 'src' ), $link ) );
        }

        $a = $item->find( 'a' );
        foreach ( $a as $i )
        {
            $i->setAttribute( 'href', $this->getUrl( $i->getAttribute( 'href' ), $link ) );
        }
        
        
        return $item->save();
    }
    
    protected function insertNew( $db, $data )
    {
        $url = parse_url( $this->params['site'] );
        $data['site'] = $url['host'];

        if ( !$db->exists( 'SELECT id FROM news WHERE title=\''.$data['title'].'\' AND site=\''.$data['site'].'\'' ) )
        {
            $db->insert( 'news', $data );
        }
    }

    public function exec( $db )
    {
        $content = $this->html->find( $this->params['content'] );
        
        
        foreach ( $content as $item )
        {
            $this->insertNew( $db, $this->parserNew( $item ) );
        }
    }
}

?>
