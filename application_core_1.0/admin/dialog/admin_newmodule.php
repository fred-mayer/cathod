<?php

    $modules = $module->admin->getModule( $template->get->idmodule );
    
    $ms = $module->getModules( $modules->name );

    $this->setTitle( 'Новый Модуль' );
    
    $form = new TForm();
    $form->beginForm();

    
    $array = array();
    $array[] = array( 'value'=>0, 'title'=>'--Новый модуль--');
    foreach ( $ms as $m )
    {
        $array[] = array( 'value'=>$m->id, 'title'=>$m->title);
    }

    
    $form->select( 'modules', 'Модуль', $array );

    $form->html( '<div class="dialognew">' );
    $form->inputText( 'name_module', 'Название модуля' );

    if ( file_exists( ADMIN_DIR.'dialog/'.$modules->name.'_new.php' ) )
    {
        include_once( ADMIN_DIR.'dialog/'.$modules->name.'_new.php' );
    }

    $form->html( '</div>' );
    
    
    if ( isset($template->get->pages) )
        $form->hidden( 'pages', $template->get->pages );

    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Создать', $this->urlAction( $template ) );
    $this->displayDialog( false );

?>
<script>
    $("#modules").bind("change", function(e){

        var id = $('#modules option:selected').val();

        if ( id === '0' )
            $('.modal-body .dialognew').show();
        else
            $('.modal-body .dialognew').hide();

        e.preventDefault();
        return false;
    });
    
    function d_complete(data)
    {
        data = eval( '('+data+')' );

        if ( data.error !== undefined )
        {
            alert( data.error );
        }
        else
        {
            $(data.content).insertBefore( "a[action='addmodule'][set_pos='<? echo $template->get->set_pos;?>']" );
        }
                
        $('#myModal').modal( 'hide' );
    };
    
    
    $(".modal-footer .btn-primary").bind("click", function(e){

        var id = $('#modules option:selected').val();

        if ( id === '0' )
        {
            $.post('<?php echo $this->urlAction( $template ); ?>', d_get_post(), d_complete);
            
            $('#myModal').html( '<span class="loading"></span>' );
        }
        else
        {
            $('#myModal').html( '<span class="loading"></span>' );
            
            $.post('/ajax/admin/admin?action=addmodule&idpage=<? echo $template->get->idpage;?>&idmodule='+id+'&set_pos=<? echo $template->get->set_pos;?>'+'&pages=<? echo $template->get->pages; ?>', function(data){

                data = eval( '('+data+')' );
                
                if ( data.error !== undefined )
                {
                    alert( data.error );
                }
                else
                {
                    $(data.content).insertBefore( "a[action='addmodule'][set_pos='<? echo $template->get->set_pos;?>']" );
                }
                
                $('#myModal').modal( 'hide' );
            });
        }
        
        e.preventDefault();
        return false;
    });
</script>
