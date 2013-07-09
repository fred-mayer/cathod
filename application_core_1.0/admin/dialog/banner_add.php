<?php

    if ( isset($template->get->id) )
    {
        $data = $module->getBannerById( $template->get->id->int() );
    }
    else
    {
        $data = null;
    }

    $this->setTitle( isset($template->get->id) ? 'Редактировать' : 'Добавить' );
    
    $form = new TForm( $data );
    $form->beginForm();
    
    $form->inputText( 'href',    'Ссылка' );
    $form->inputFile( 'file',   'Файл изображения' );
    //$form->inputText( 'url',     'URL изображения' );
    $form->html( '<div class="control-group"><div class="controls"><img id="file-img"'.($data !== null ? ' src="/media/banner/banner_'.$data->id.'.'.$data->src.'"' : '').' /></div></div>' );
    $form->inputText( 'level',   'Позиция' );

    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog( false );

?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', '<?php echo $this->urlAction( $template ); ?>', true );
        xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');
        
        var formData = new FormData();
        formData.append('href', $('#href').val());
        //formData.append('url', $('#url').val());
        formData.append('level', $('#level').val());
        formData.append('file', $('#file').get(0).files[0]);
        
        //alert($('#file').get(0).files[0]);
        
        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 ) 
                    
                    //$('#myModal').html( this.response );
                    $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit'; ?>" );
            }
        };
        
        xhr.upload.addEventListener( 'progress', function(e){

            if ( e.lengthComputable )
            {
                $('#myModal #myModalLabel').get(0).innerHTML = Math.round( e.loaded / e.total * 100 ) + '%';
            }
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
    
    $("#file").bind("change", function(e){

        var fr = new FileReader();
        fr.onload=function(){

            $("#file-img").get(0).src = this.result;
        };
        fr.readAsDataURL( e.target.files[0] );
    });

    function d_complete()
    {
        $('#myModal').html( '<p>Загрузка</p>' );
        $('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit'; ?>" );
    };
</script>
