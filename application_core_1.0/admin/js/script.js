
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
        
        var self = this;
        
        $.post('/ajax/admin?action='+$(this).attr('action')
               +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
               +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
               +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
               +(( $(this).attr('level') !== undefined ) ? '&level='+$(this).attr('level') : ''), function(){

                
                    var el1 = $(self).parent().parent().parent();
                    if ( el1.next('.admin-module').length )
                    {
                        var el2 = el1.next('.admin-module');
                        
                        var el1_move = el1.find('.move[action=downmodule]');
                    
                        var level1 = parseInt( el1_move.attr('level') );
                        el1_move.attr( 'level', level1 + 1 );
                        
                        var el2_move = el2.find('.move[action=downmodule]');
                    
                        var level2 = parseInt( el2_move.attr('level') );
                        el2_move.attr( 'level', level2 - 1 );
                        

                        var el1_clone = el1.clone(true);
                        var el2_clone = el2.clone(true);

                        el1.replaceWith( el2_clone );
                        el2.replaceWith( el1_clone );
                    }


                    $('#myModal').modal( 'hide' );
               });

        e.preventDefault();
        return false;
    });
    
    // Поднять модуль (Админ)
    $(".btn-toolbar .btn-group .move[action=upmodule]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );
        
        var self = this;
        
        $.post('/ajax/admin?action='+$(this).attr('action')
               +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
               +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
               +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
               +(( $(this).attr('level') !== undefined ) ? '&level='+$(this).attr('level') : ''), function(){

                
                    var el1 = $(self).parent().parent().parent();
                    if ( el1.prev('.admin-module').length )
                    {
                        var el2 = el1.prev('.admin-module');
                        
                        var el1_move = el1.find('.move[action=upmodule]');
                    
                        var level1 = parseInt( el1_move.attr('level') );
                        el1_move.attr( 'level', level1 - 1 );
                        
                        var el2_move = el2.find('.move[action=upmodule]');
                    
                        var level2 = parseInt( el2_move.attr('level') );
                        el2_move.attr( 'level', level2 + 1 );
                        

                        var el1_clone = el1.clone(true);
                        var el2_clone = el2.clone(true);

                        el1.replaceWith( el2_clone );
                        el2.replaceWith( el1_clone );
                    }


                    $('#myModal').modal( 'hide' );
               });

        e.preventDefault();
        return false;
    });