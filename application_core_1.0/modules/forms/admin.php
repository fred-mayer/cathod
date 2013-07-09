<?php

class Tadmin_forms extends TBAdmin
{
    public function insert( $post )
    {
        //записываем почту
        $id = $this->db->insert("forms",array('name'=>$post->name_module,'mailto'=>$post->mailto,'mailfrom'=>$post->mailfrom,'subject'=>$post->subject));
        return array('id'=>$id);
    }
    public function settings($get,$post)
    {
        $this->db->update("forms",array('name'=>$post->name,'mailto'=>$post->mailto,'mailfrom'=>(($post->mailfromfield!="0")? $post->mailfromfield:$post->mailfrom),'subject'=>$post->subject,'textSuccess'=>$post->afterSend),"id=".$get->id);
    }
    public function getFormSettings($idform)
    {
        return $this->db->select("SELECT * FROM forms WHERE id=".$idform)->current();
    }
    public function getFormFields($idform)
    {
        return $this->db->select("SELECT * FROM forms_fields WHERE id_form=".$idform." ORDER BY `order` ASC")->toObject();
    }
}

?>