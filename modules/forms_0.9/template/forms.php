<?php

    $data = $this->getData();

    if ( isset($data['form']) && isset($data['form_fields']) && isset($data['class_form']) )
    {
        $form = $data['form'];
        $fields = $data['form_fields'];
        $clForm = $data['class_form'];
    }
?>
    <?php //проверка заполненности всех полей для отображения модуля или магазина
    if(isset($data['errors'])): ?>
        <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Предупреждение!</strong> <? echo $data['errors'] ?>
        </div>
    <?php else: 
        $clForm->beginForm("form-contact".$form->id,"form-contact".$form->id,$_SERVER['REQUEST_URI']."forms/".$form->id,"post","form-inline");
        $clForm->legend($form->name);
        $clForm->hidden("id_form",$form->id);
        
        foreach($fields as $field): 
            $clForm->insertField($field->type,$field->name,$field->label,"","",(($field->is_required=="yes")? "required":""),$field->placeholder,$field->pattern );
        endforeach; 
        $clForm->submit("Отправить");
        $clForm->endForm();
        echo $clForm;
        ?>
    <? endif; ?>