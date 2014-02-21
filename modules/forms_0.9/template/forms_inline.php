<?php

    $data = $this->getData();
    $form = $data['form'];
    $fields = $data['form_fields'];
    $clForm = $data['class_form'];
?>
    <?php //проверка заполненности всех полей для отображения модуля или магазина
    if(isset($data['errors'])): ?>
        <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Предупреждение!</strong> <? echo $data['errors'] ?>
        </div>
    <?php else: 
        $clForm->beginForm("form-contact".$form->id,"form-contact".$form->id,$_SERVER['REQUEST_URI']."forms/".$form->id,"post","form-inline");
        $clForm->html('<div class="modal hide fade" id="feedCall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">'.$form->name.'</h3>
        </div>
        <div class="modal-body">');
        $clForm->hidden("id_form",$form->id);
        
        foreach($fields as $field): 
            $clForm->insertField($field->type,$field->name,$field->label,"","",(($field->is_required=="yes")? "required":""),$field->placeholder,$field->pattern );
        endforeach; 
        
        $clForm->html('</div><div class="modal-footer">');
        $clForm->submit("Отправить");
        $clForm->html('</div></div>');
        $clForm->endForm();
        echo $clForm;
        ?>
    <? endif; ?>