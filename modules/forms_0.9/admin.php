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
    
    public function getFormFieldById( $id )
    {
        return $this->db->select( "SELECT * FROM forms_fields WHERE id=".$id )->current();
    }
    
    private function getIdFormByModule($idmodule)
    {
        $params = json_decode( $this->db->select( 'SELECT params FROM core_modules WHERE id='.$idmodule )->current('params'), true );
        
        return $params['id'];
    }
    
    // добавляем поля формы
    public function insert_fields( $get, $post )
    {
        //var_dump($get);
        //var_dump($post);
        //var_dump( $_FILES);

        if ( isset($post->id) )
        {
            $this->db->update( 'forms_fields', array(
                'name'=>$post->name,
                'label'=>$post->label,
                'placeholder'=>$post->placeholder,
                'type'=>$post->type,
                'is_required'=>$post->is_required,
                'pattern'=>$post->pattern ), "id=".$post->id->int() );
            
            $this->level( $post->id_form->int(), $post->id->int(), $post->order->int() );
        }
        else
        {
            $maxlevel = $this->db->select( 'SELECT MAX(`order`) AS maxlevel FROM forms_fields WHERE id_form='.$post->id_form->int() )->current( 'maxlevel' );
            
            $id = $this->db->insert( 'forms_fields', array( 'id_form'=>$post->id_form->int(),
                                                            'name'=>$post->name,
                                                            'label'=>$post->label,
                                                            'placeholder'=>$post->placeholder,
                                                            'type'=>$post->type,
                                                            'is_required'=>$post->is_required,
                                                            'pattern'=>$post->pattern,
                                                            'order'=>$maxlevel ));
            
            $this->level( $post->id_form->int(), $id, $post->order->int() );
        }
    }
    
    public function del_fields( $get, $post )
    {
        $this->db->query( 'DELETE FROM forms_fields WHERE id='.$get->id->int() );
    }

    protected function level( $id_form, $id, $level )
    {
        $l = $this->db->select( 'SELECT `order` FROM forms_fields WHERE id='.$id )->current( 'order' );
        
        if ( $l == $level ) return;


        $maxlevel = $this->db->select( 'SELECT MAX(`order`) AS maxlevel FROM forms_fields WHERE id_form='.$id_form )->current( 'maxlevel' );
        
        if ( $level > $maxlevel ) $level = $maxlevel;


        if ( $level < $l )
        {
            $this->db->query( 'UPDATE forms_fields SET `order`=`order`+1 WHERE `order`>='.$level.' AND `order`<'.$l.' AND id_form='.$id_form );
            $this->db->query( 'UPDATE forms_fields SET `order`='.$level.' WHERE id='.$id );
        }
        elseif ( $level > $l )
        {
            $this->db->query( 'UPDATE forms_fields SET `order`=`order`-1 WHERE `order`<='.$level.' AND `order`>'.$l.' AND id_form='.$id_form );
            $this->db->query( 'UPDATE forms_fields SET `order`='.$level.' WHERE id='.$id );
        }
    }
}

?>