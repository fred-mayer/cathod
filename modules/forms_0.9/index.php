<?php

class Tforms extends TModule
{
    public function display( TTemplate $template )
    {
        $db = $this->template->db;
        $params = $this->getParams();
        $data = array();
        // загрузили форму
        $form = $db->select("SELECT * FROM forms WHERE id=".$params['id'])->current();
        // загружаем поля
        $formFields = $db->select("SELECT * FROM forms_fields WHERE id_form=".$params['id'] ." ORDER BY `order` ASC")->toObject();
        if(count($formFields)){
            $data['form'] = $form;
            $data['form_fields'] = $formFields;
            $data['class_form'] = new TForm_user('','/ajax/forms?action=sendform');
            //$template->setScript('jquery.validate.min.js'); //подключаем скрипт валидации
        }else{
            if($this->template->auth->isAuthorized){
                $data['errors'] = "Форма пуста! Добавьте в форму элементы!";
            }
        }
        $this->data = $data;
        
        parent::display( $template );
    }
    public function getAjaxDisplay(){
        
        $this->display($this->template);
    }
    public function sendform($get,$post){
        $db = $this->template->db;
        if(isset($post->id_form)){
            //считываем поля формы!
            $res = $db->select("SELECT * FROM forms_fields WHERE id_form=".$post->id_form. " ORDER BY `order` ASC")->toObject();
            if(count($res)){
                include_once (CLASS_DIR.'mail.php');
                $mail = new TMail();
                $message = "";
                foreach($res as $field){
                    if($field->type !='file'){
                        if($post[$field->name]){
                          $message .= "<p><strong>".$field->label.":</strong> ".$post[$field->name]."</p>"; 
                        }elseif($field->is_required){
                            $error = 1; //заметили ошибку
                        }
                    }else{//если файл отправляем
                    	if($_FILES[$field->name]['tmp_name']){
                        	//проверка типа файла
                        	$type = $_FILES[$field->name]['type'];
                        
                        	$mail->addAttach($field->name);
                        }
                    }
                }
                //подготавливаем к отправке
                $form = $db->select("SELECT * FROM forms WHERE id=".$post->id_form)->current();
                
                $mail->addRecipient($form->mailto);
                $mail->setSubject($form->subject);
                $mailfrom = $form->mailfrom;
                if(!strstr($form->mailfrom,"@")){ //если не почта а поле
                    if(isset($post[$form->mailfrom]) && filter_var($post[$form->mailfrom], FILTER_VALIDATE_EMAIL)){
                        $mailfrom = $post[$form->mailfrom];
                    }
                }
                $mail->setSender($mailfrom);
                $mail->setBody($message, true);
                if(!isset($error)){
                    if($mail->send())
                    {
                        echo $form->textSuccess;
                    }
                }else{
                    echo $error;
                }
            }
            
        }
    }
    public function getAdminToolbar( $attr, $buttons=null )
    {
        $buttons[] = array('action'=>'settings', 'icon'=>'wrench', 'text'=>'Настройки', 'title'=>'');
        $buttons[] = array('action'=>'edit', 'icon'=>'pencil', 'text'=>'Редактировать', 'title'=>'');
        
        return parent::getAdminToolbar( $attr, $buttons );
    }
}

?>