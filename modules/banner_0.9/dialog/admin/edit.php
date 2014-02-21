<?php

    $data = $module->setBanners();

    $this->setTitle( 'Баннер' );
    ob_start();
?>
    <button class="btn" id="button_add"><i class="icon-plus"></i></button>
    <table>
<?php
    foreach ( $data as $row )
    {
?>
        <tr>
            <td width="70%"><img src="/media/banner/banner_<?php echo $row->id.'.'.$row->src; ?>"  width="100%" /></td>
            <td valign="top"><button class="btn btn-mini button_edit" idbanner="<? echo $row->id; ?>"><i class="icon-pencil"></i></button>
                <button class="btn btn-mini button_del" idbanner="<? echo $row->id; ?>"><i class="icon-remove"></i></button></td>
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

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=add'; ?>", {}, function(data){
            
            $('#myModal').html(data);
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
    
    $(".button_edit").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=add&id='; ?>"+$(this).attr('idbanner'), {}, function(data){
            
            $('#myModal').html(data);
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
    
    $(".button_del").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?action=del&id='; ?>"+$(this).attr('idbanner'), {}, function(data){
            
            $('#myModal').html( '<p>Загрузка</p>' );
            $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit'; ?>" );
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
</script>