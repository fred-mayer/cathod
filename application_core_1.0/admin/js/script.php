$(function(){
<?php

        $files = scandir( MODULES_DIR );

        foreach ( $files as $file )
        {
            if ( is_dir( MODULES_DIR.'/'.$file ) )
            {
                if ( file_exists( MODULES_DIR.'/'.$file.'/script.js' ) )
                    include_once( MODULES_DIR.'/'.$file.'/script.js' );
            }
        }

        include_once( 'script.js' );
?>

/*
    // Диалог удаления модуля (Админ)
    $(".btn-toolbar .btn-group .btn[action=delmodule]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );

        $('#myModal').load( '/ajax/admin?dialog='+$(this).attr('action')
                    +(( $(this).attr('id') !== undefined ) ? '&id='+$(this).attr('id') : '')
                    +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
                    +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
                    +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '') );
        e.preventDefault();
        return false;
    });
    
    
    // Опустить модуль (Админ)
    $(".btn-toolbar .btn-group .move[action=downmodule]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );
        
        $.post('/ajax/admin?action='+$(this).attr('action')
               +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
               +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
               +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
               +(( $(this).attr('level') !== undefined ) ? '&level='+$(this).attr('level') : ''), function(){

                    var level = $(this).attr('level');
                    $(this).attr( 'level', level+1 );

                    $('#myModal').modal( 'hide' );
               });

        e.preventDefault();
        return false;
    });*/
    
    
    
    // Диалог ред. модуля
    $(".btn-toolbar .btn-group .btn[module!=admin]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );

        $('#myModal').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')
                    +(( $(this).attr('id') !== undefined ) ? '&id='+$(this).attr('id') : '')
                    +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
                    +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
                    +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
                    +(( $(this).attr('more') !== undefined ) ? $(this).attr('more') : ''));
        e.preventDefault();
        return false;
    });
    


    // Диалог добавления нового модуля в позицию
    $(".btn[set_pos]:not(.btn-toolbar .btn-group .btn)").bind("click", function(e){

        dialog.load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')+'&idpage='+$(this).attr('idpage')+'&set_pos='+$(this).attr('set_pos') );
        //$('#myModal').html( '<div class="modal-tab"><span class="loading"></span></div>' );
        //$('#myModal').modal( 'show' );
        //$('#myModal .modal-tab').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')+'&idpage='+$(this).attr('idpage')+'&set_pos='+$(this).attr('set_pos') );

        e.preventDefault();
        return false;
    });


    $("#admin-sub-panel").click(function(){
		$("#admin-panel").slideToggle();	
		/*var el = $("#shText");  
		var state = $("#shText").html();

		//state = (state == 'Hide' ? '<span id="shText">Админ</span>' : '<span id="shText">Админ</span>');					

		el.replaceWith(state); */
    });
    
    
    // Диалог ред. страницу
    $(".btn-toolbar-admin .btn-group .btn[module]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );

        $('#myModal').load( '/ajax/admin?dialog='+$(this).attr('action')
                    +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '') );
        e.preventDefault();
        return false;
    });
});