<?php
$formSite = $module->admin->getFormSettings($template->get->idmodule);
$fields = $module->admin->getFormFields($template->get->idmodule);
$this->setTitle( 'Редактировать форму' );
    $form = new TForm();
    $form->beginForm();
    $form->inputText( 'name',"Название формы",$formSite->name );
    $form->inputText( 'mailto', 'E-mail куда отправлять',$formSite->mailto );
    $form->inputText( 'mailfrom', 'E-mail от кого',$formSite->mailfrom );
    //$fieldsRow;
    $fieldsRow[0]['title'] = "Значение поля...";
    $fieldsRow[0]['value'] = 0;
    for($i=0;$i<count($fields);$i++){
       $fieldsRow[$i+1]['title'] = "{".$fields[$i]["name"]."} - ". $fields[$i]["label"];
       $fieldsRow[$i+1]['value'] = $fields[$i]["name"];
    }
    $form->select( 'mailfromfield', 'или берем с поля',$fieldsRow,$formSite->mailfrom );
    $form->addScript(
            '$("#mailfromfield").change(function(){
                if($(this).val()!=0){
                    $("#mailfrom").prop("disabled",true);
                }else{ $("#mailfrom").prop("disabled",false); }
            });'
            );
    $form->inputText( 'subject', 'Тема письма',$formSite->subject );
    
    $form->hr();
    $form->ckeditor('afterSend',"Текст после отправки письма", $formSite->textSuccess);
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();
    
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        //$('.forms.admin-module[idform=<? echo $template->get->id ?>]').load('/ajax/forms?action=getAjaxDisplay');
        //location.reload();
    };
</script>
