<?php

    $this->setTitle( 'Парсер' );
    
    $form = new TForm();
    $form->beginForm();
    
    $form->inputFile( 'file',   'Файл xml' );

    $form->endForm();

    $this->setBody( $form );
    
    $this->setNameButtonPrimary( 'Выполнить', $this->urlAction( $template, '', true ) );
    $this->displayDialog( false );

?>
<script>
    $(".modal-footer .btn-primary").bind("click", function(e){

        var xhr = new XMLHttpRequest();
        xhr.open( 'POST', '<?php echo $this->urlAction( $template, '', true ); ?>', true );
        xhr.setRequestHeader('X-Requested-With', 'XmlHttpRequest');
        
        var formData = new FormData();
        formData.append('file', $('#file').get(0).files[0]);
        
        //alert($('#file').get(0).files[0]);
        
        xhr.onreadystatechange = function(){

            if ( this.readyState === 4 ) // запрос завершён
            {
                if ( this.status === 200 ) 
                    
                    if ( this.response === '' )
                    {
                        document.location.reload(); // обновить страницу
                    }
                    else
                        $('#myModal').html( this.response );
            }
        };
        
        xhr.upload.addEventListener( 'progress', function(e){

            if ( e.lengthComputable )
            {
                $('#myModal #myModalLabel').get(0).innerHTML = 'Загрузка файла - '+Math.round( e.loaded / e.total * 100 ) + '%';
            }
        });
        
        xhr.upload.addEventListener( 'load', function(e){

            $('#myModal #myModalLabel').get(0).innerHTML = 'Парсер...';
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
        
    };
</script>

