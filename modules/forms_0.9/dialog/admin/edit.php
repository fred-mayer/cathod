<?php
$formSite = $module->admin->getFormSettings($template->get->idmodule);
$this->setTitle( 'Редактировать форму - '. $formSite->name );

$formFields = $module->getFields( $formSite->id );


$option = array( 'inputText'=>'Строка', 'textarea'=>'Текст', 'email'=>'Адрес электронной почты', 'phone'=>'Телефонный номер', 'file'=>'Файл', 'select'=>'Список' );

    ob_start();
?>
    <button class="btn" id="button_add"><i class="icon-plus"></i></button>
    <table>
<?php
    foreach ( $formFields as $field )
    {
?>
        <tr>
            <td width="70%"><?php echo $field->label.' ('.$option[$field->type].')'; ?></td>
            <td valign="top"><button class="btn btn-mini button_edit" idfield="<? echo $field->id; ?>"><i class="icon-pencil"></i></button>
                <button class="btn btn-mini button_del" idfield="<? echo $field->id; ?>"><i class="icon-remove"></i></button></td>
        </tr>
<?php
    }
?>
    </table>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    
    $this->setBody( $html );
    $this->displayDialog();
    
?>
<script>
    $("#button_add").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=add&id_form='.$formSite->id.'&idmodule='.$template->get->idmodule; ?>", {}, function(data){
            
            $('#myModal').html(data);
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
    
    $(".button_edit").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=add&id_form='.$formSite->id.'&idmodule='.$template->get->idmodule.'&id='; ?>"+$(this).attr('idfield'), {}, function(data){
            
            $('#myModal').html(data);
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
    
    $(".button_del").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?action=del_fields&id_form='.$formSite->id.'&id='; ?>"+$(this).attr('idfield'), {}, function(data){
            
            $('#myModal').html( '<p>Загрузка</p>' );
            $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit&idmodule='.$template->get->idmodule; ?>" );
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
</script>