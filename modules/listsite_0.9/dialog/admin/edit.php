<?php

    if ( isset($template->get->idmodule) )
    {
        $data = $module->getSiteById( $template->get->idmodule->int() );
    }
    else
    {
        $data = null;
    }

    $this->setTitle( isset($template->get->idmodule) ? 'Редактировать' : 'Добавить' );
    
    $form = new TForm( $data );
    $form->beginForm();
    
    $form->inputText( 'site',    'Имя сайта' );
    $form->inputText( 'url',     'URL' );
    $form->inputFile( 'file',    'Логотип' );
    //$form->inputText( 'url',     'URL изображения' );
    $form->html( '<div class="control-group"><div class="controls"><img id="file-img"'.($data !== null ? ' src="/media/logos/'.$data->logo.'"' : '').' /></div></div>' );
    $form->textarea( 'descripion',      'Описание краткое' );
    $form->textarea( 'descripion_all',  'Описание полное' );

    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template, '', true ) );
    $this->displayDialog( false );

?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', '<?php echo $this->urlAction( $template, '', true ); ?>', true );
        xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');
        
        var formData = new FormData();
        formData.append('site', $('#site').val());
        formData.append('url', $('#url').val());
        formData.append('descripion', $('#descripion').val());
        formData.append('descripion_all', $('#descripion_all').val());
        formData.append('file', $('#file').get(0).files[0]);
        
        //alert($('#file').get(0).files[0]);
        
        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 )
                {
                    
                    //$('#myModal').html( this.response );
                    //$('#myModal').load( "<? echo '/ajax/admin/'.$template->route[0].'?dialog=edit'; ?>" );
                    document.location.reload(); // обновить страницу
                }
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
