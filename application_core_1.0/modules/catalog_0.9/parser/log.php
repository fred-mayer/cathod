<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class log
{
    public $sep;
    public $db;
    
    function __construct($db) {
        $this->db = $db;
    }
    function printr($data){
        echo "<p><small>".$data."</small></p>";
    }
    function getStart(){
        $this->printr(date("d.m.Y i:H"));
    }
    function saveItem($item){
        $this->printr("Add item: Артикул-".$item['articul'].", Назв-".$item['name'].", Цена-".$item['price'].", url-".$item['url']);
    }
    function saveStep($id_mag,$id_cat){
        $log = $this->db->select("SELECT * FROM parser_logs WHERE id_mag=".$id_mag)->current();
        if($log!==false){
            $this->db->update("parser_logs",array("id_cat"=>$id_cat),"id_mag=".$id_mag);
        }else{
            $this->db->insert("parser_logs",array("id_cat"=>$id_cat,"id_mag"=>$id_mag));
        }
    }
    function loadStep($id_mag){
        return $this->db->select("SELECT id_cat FROM parser_logs WHERE id_mag=".$id_mag)->current("id_cat");
    }
    
}
?>
