<?php

    $data = $module->admin->getParse();

    $this->setTitle( 'Парсер' );
    ob_start();
?>
    <button class="btn" id="button_add"><i class="icon-plus"></i></button>
    <table>
<?php
    foreach ( $data as $row )
    {
?>
        <tr>
            <td width="100%"><a href="<? echo $row->site; ?>"<? if ($row->parser == 'off') echo ' style="text-decoration:line-through;"'; ?> target="_blank"><? echo $row->site; ?></a></td>
            <td><button class="btn btn-mini button_edit" idparser="<? echo $row->id; ?>"><i class="icon-pencil"></i></button></td>
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

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=editparser'; ?>", {}, function(data){
            
            $('#myModal').html(data);
        });

        e.preventDefault();
        return false;
    });
    
    $(".button_edit").bind("click", function(e){

        $.post("<? echo '/ajax/admin/'.$template->route[0].'?dialog=editparser&id='; ?>"+$(this).attr('idparser'), {}, function(data){
            
            $('#myModal').html(data);
        });

        e.preventDefault();
        return false;
    });
</script>