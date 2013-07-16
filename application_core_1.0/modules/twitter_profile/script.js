
    $(".btn-toolbar .btn-group .btn[module=twitter_profile][action=del]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );

        $('#myModal').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')
                    +(( $(this).attr('id') !== undefined ) ? '&id='+$(this).attr('id') : '')
                    +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
                    +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
                    +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
                    +(( $(this).attr('more') !== undefined ) ? $(this).attr('more') : '') );
        e.preventDefault();
        return false;
    });