<?php

    $data = $module->admin->getAllModules();

    $this->setTitle( 'Список модулей' );
    ob_start();

?>

        <div class="control-group">
            <div class="controls clearfix">
                <span class="btn btn-success btn-file">
                    <i class="icon-plus"></i><span> Установить</span>
                    <input type="file" name="image" id="button_install" />
                </span>
            </div>
        </div>

    <table class="table table-bordered">
        <tr>
            <td>Название</td>
            <td>Описание</td>
            <td>Модуль</td>
            <td>Версия</td>
            <td></td>
        </tr>
<?php

    foreach ( $data as $row )
    {
?>
        <tr>
            <td><? echo $row->title.' '.$row->version; ?></td>
            <td><? echo $row->descripion; ?></td>
            <td><? echo $row->name; ?></td>
            <td><? echo $row->version; ?></td>
            <td>
                <a href="" class="btn btn-mini button_import" idmodule="<? echo $row->id; ?>" title="Импортировать модуль"><i class="icon-share"></i></a>
                <a href="" class="btn btn-mini button_uninstall" idmodule="<? echo $row->id; ?>" title="Удалить модуль"><i class="icon-remove"></i></a>
            </td>
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
    $("#button_install").bind("change", function(e){
        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', '<? echo '/ajax/admin/admin?action=installmodule'; ?>', true );
        xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');

        var formData = new FormData();
        formData.append('file', $('#button_install').get(0).files[0]);

        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 ) 
                {
                    //$('#myModal').html( this.response );
                    $('#myModal').load( "<? echo '/ajax/admin/admin?dialog=listmodule'; ?>" );
                }
            }
        };
        
        xhr.upload.addEventListener( 'progress', function(e){

            if ( e.lengthComputable )
            {
                $('#myModal #myModalLabel').get(0).innerHTML = 'Загрузка файла - '+Math.round( e.loaded / e.total * 100 ) + '%';
            }
        });
        
        xhr.upload.addEventListener( 'load', function(e){

            $('#myModal #myModalLabel').get(0).innerHTML = 'Установка...';
        });
        
        
        xhr.send(formData);
        
            
            $('#myModal').html( '<div class="modal-header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                                    '<h3 id="myModalLabel"></h3>'+
                                '</div>'+
                                '<div class="modal-body"><span class="loading"></span></div>' );

        e.preventDefault();
        return false;
    });

    function d_complete()
    {
        //$('#myModal').html( '<p>Загрузка</p>' );
        //$('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit'; ?>" );
    };
    
    /*$("#button_install").bind("click", function(e){

        $.post("<? echo '/ajax/admin/admin?action=installmodule'; ?>", {}, function(data){
            
            $('#myModal').html(data);
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });*/
    
    $(".button_import").bind("click", function(e){

        $.post("<? echo '/ajax/admin/admin?action=importmodule&id='; ?>"+$(this).attr('idmodule'), {}, function(data){
            
            $('#myModal').html('<div class="modal-header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                                    '<h3 id="myModalLabel">Импорт...</h3>'+
                                '</div>'+
                                '<div class="modal-body">'+data+'</div>');
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
    
    $(".button_uninstall").bind("click", function(e){

        $.post("<? echo '/ajax/admin/admin?action=uninstallmodule&id='; ?>"+$(this).attr('idmodule'), {}, function(data){

            $('#myModal').load( "<? echo '/ajax/admin/admin?dialog=listmodule'; ?>" );
        });
        
        $('#myModal').html( '<span class="loading"></span>' );

        e.preventDefault();
        return false;
    });
</script>