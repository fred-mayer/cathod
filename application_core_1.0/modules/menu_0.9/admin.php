<?php

class Tadmin_menu extends TBAdmin
{
    public function insert( $post )
    {
        return array( 'name_group'=>(string)$post->name_group,'template'=>(string)$post->template );
    }
    public function add( $get, $post )
    {
        $array["title"]=(string)$post->title;
        if(isset($post->href))
            $array["href"]=(string)$post->href;
        if($post->id_page->int()!=0)
             $array["id_page"]=(string)$post->id_page;
        if($post->id_parent!="-1")
            $array["id_parent"]=(string)$post->id_parent;
        $array["sfx"]=(string)$post->sfx;
        if($post->separator=="1")
            $array["separator"] = "yes";
        $array["name_group"]=(string)$post->name_group;
        //изменяем порядок
        $array["order"]=(string)$post->order;
        if($this->db->select("SELECT id FROM `menu` WHERE `order`>=".$post->order)->count()){
            $chanheItems = $this->db->select("SELECT id,`order` FROM `menu` WHERE `order`>=".$post->order)->toObject();
            foreach($chanheItems as $item){
                $curOrder = $item->order;
                $curOrder++;
                $this->db->update("menu",array("order"=>$curOrder)," id=".$item->id);
            }
        }
        
        
        $this->db->insert("menu",$array);
        echo "Пункт меню добавлен!";
    }
    public function getPages()
    {
        return $this->db->select("SELECT * FROM core_page WHERE `alias`!='default'")->toObject();
    }
    public function getMenu($name_group)
    {
        $sql ="SELECT * FROM `menu` WHERE name_group='".$name_group."'";
        if($this->db->select($sql)->current()){
            return $this->db->select($sql)->toObject();
        }else{
            return false;
        }
    }
    
}

?>