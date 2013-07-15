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
        $id = $this->getIdFormByModule($get->idmodule);
        $this->db->update("forms",array('name'=>$post->name,'mailto'=>$post->mailto,'mailfrom'=>(($post->mailfromfield!="0")? $post->mailfromfield:$post->mailfrom),'subject'=>$post->subject,'textSuccess'=>$post->afterSend),"id=".$id);
    }
    public function getFormSettings($idmodule)
    {
        $idform = $this->getIdFormByModule($idmodule);
        return $this->db->select("SELECT * FROM forms WHERE id=".$idform)->current();
    }
    public function getFormFields($idmodule)
    {
        $idform = $this->getIdFormByModule($idmodule);
        return $this->db->select("SELECT * FROM forms_fields WHERE id_form=".$idform." ORDER BY `order` ASC")->toObject();
    }
    
    private function getIdFormByModule($idmodule)
    {
        $params = json_decode( $this->db->select( 'SELECT params FROM core_modules WHERE id='.$idmodule )->current('params'), true );
        
        return $params['id'];
    }
}

?>