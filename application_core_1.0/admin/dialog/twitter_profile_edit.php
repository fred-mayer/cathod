<?php

    $data = $module->admin->get( $template->get->idprofile->int() );

    $this->setTitle( 'Редактировать профиль' );

    $form = new TForm( $data );
    $form->beginForm();
    $form->inputText( 'name', 'Имя' );
    $form->inputText( 'nickname', 'Ник' );
    
    $form->inputFile( 'file',   'Аватарка' );
    //$form->inputText( 'url',     'URL изображения' );
    $form->html( '<div class="control-group"><div class="controls"><img id="file-img"'.($data->img !== '' ? ' src="/media/profile/'.$data->img.'"' : 'src=""').' /></div></div>' );
    
    
    $form->hidden( 'idprofile', $template->get->idprofile->int() );
    
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
        formData.append('name', $('#name').val());
        formData.append('nickname', $('#nickname').val());
        formData.append('idprofile', $('#idprofile').val());
        formData.append('file', $('#file').get(0).files[0]);
        
        //alert($('#file').get(0).files[0]);
        
        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 ) 
                    
                    //$('#myModal').html( this.response );
                    d_complete(this.response);
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

    function d_complete(data)
    {
        //alert(data);

        location.reload();
    };
</script>
