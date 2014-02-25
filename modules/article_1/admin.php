<?php

class Tadmin_article extends TBAdmin
{
    public function getCats(){
        return $this->db->select("SELECT * FROM article_category WHERE hide='show' ORDER BY id ASC")->toObject();
    }
    public function getItem($id){
        return $this->db->select("SELECT * FROM article_items WHERE id=".$id)->current();
    }
    
    public function edit( $get, $post )
    {
        $id = $this->getIdContent( $get->idmodule->int() );
        
        $this->db->update( 'content', array('content'=>$post->content), 'id='.$id );
        
        echo $post->content;
    }
    
    public function insert( $post )
    {
        if(count($post->newcat)){
            $alias = new TString($post->newcat);
            $id = $this->db->insert( 'article_category', array('name'=>$post->newcat,'alias'=>$alias->toTranslit()) );
        }else{
            $id = $post->cat;
        }
        return array( 'idcat'=>(string) $id );
    }
    public function settings($get, $post){
        TObject::valueObjectToString($post);
        $params = array();
        $params = array_merge($params, $this->insert($post));
        if($post->template!="default")
            $params['template'] = (string) $post->template;
        if($post->cols!="default")
            $params['cols'] = (string) $post->cols;
        if($post->counts>0)
            $params['counts'] = (string) $post->counts;
        if(count($post->mainlink))
            $params['mainlink'] = (string) $post->mainlink;
        if(count($post->sfx))
            $params['sfx'] = (string) $post->sfx;
        
        //записываем данные
        $this->saveModuleParams($get->idmodule,$params);
        if(count($post->name))
            $this->changeNameModule($get->idmodule,$post->name);
    }
    protected function setAlias($alias, $n=0){
    	$uri = ($n!==0)? $alias.$n:$alias;
	    $id = $this->db->select("SELECT id FROM article_items WHERE alias='{$uri}'")->current("id");
	    if($id){
		    return $this->setAlias($alias,$n+1);
	    }else{
		    return $uri;
	    }
    }
    public function add($get, $post){
        $img = null;
        if ( isset($_FILES['img']) )
        {
            $file = $_FILES['img'];
            $img = $this->uploadImage($file['tmp_name']);
            
        }
        //продолжаем добавление в бд
        $alias = new TString($post->title);
        $alias = $this->setAlias($alias->toURI());
        
        $this->db->insert('article_items',array('id_cat'=>$post->idcat,'title'=>$post->title,'alias'=>$alias,'introtext'=>$post->content,'image'=>$img,'url_readmore'=>$post->url));
        echo "Статья " .$post->title . " сохранена!";
    }
    public function editItem($get,$post){
        $img = null;
        if ( isset($_FILES['img']) )
        {
            $file = $_FILES['img'];
            $img = $this->uploadImage($file['tmp_name']); 
        }
        $alias = new TString($post->title);
        $alias = $alias->toURI();
        $update = array();
        $update['title'] = $post->title;
        $update['alias'] = $alias;
        $update['introtext'] = $post->content;
        $update['url_readmore'] = $post->url;
        $update['img_readmore'] = ($post->img_readmore=="show")? $post->img_readmore:"hide";
        if($post->delImg=="1" || $img!==null){
            $update['image'] = $img;
        }
        if($this->db->update('article_items',$update,"id=".$post->id)){
            echo "Статья успешно изменена.";
        }
    }
    public function delItem($get,$post){
        if($post->id && $this->db->update("article_items",array('hide'=>'hide'),"id=".$post->id)){
            echo "Статья удалена.";
        }
    }
    
    private function uploadImage($tmp_filename){
        if ( is_uploaded_file( $tmp_filename ) )
            {
                $info = GetImageSize( $tmp_filename );
                
                if ( $info[2] == IMAGETYPE_JPEG || $info[2] == IMAGETYPE_PNG || $info[2] == IMAGETYPE_GIF )
                {
                    if ( $info[2] == IMAGETYPE_JPEG ) $e = 'jpg';
                    if ( $info[2] == IMAGETYPE_PNG ) $e = 'png';
                    if ( $info[2] == IMAGETYPE_GIF ) $e = 'gif';
                    $imgname = $this->getName('media/',$e);
                    $img = 'media/'.$imgname;
                    
                    move_uploaded_file( $tmp_filename, $img );
                }
            }
            return $img;
    }
    
    private function getName($path,$ext){
        $imgname = TAuth::generateHash().".".$ext;
        if(file_exists($path.$imgname)){
            return $this->getName($path,$ext);
        }else{
            return $imgname;
        }
    }
    
}

?>