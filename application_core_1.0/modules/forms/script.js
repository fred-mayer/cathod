

$("div.forms[idmodule]").each(function(){
    $(this).prepend('<div class="btn-toolbar"><div class="btn-group">'+
                        '<a class="btn btn-mini" module="admin" action="'+($(this).hasClass('unpublished') ? 'show' : 'hide')+'module" idpage="'+$(this).attr('idpage')+'" idmodule="'+$(this).attr('idmodule')+'" set_pos="'+$(this).attr('set_pos')+'" href="#"><i class="icon-eye-'+($(this).hasClass('unpublished') ? 'open' : 'close')+'"></i></a>'+
                        '<a class="btn btn-mini" module="admin" action="delmodule" idpage="'+$(this).attr('idpage')+'" idmodule="'+$(this).attr('idmodule')+'" set_pos="'+$(this).attr('set_pos')+'" href="#"><i class="icon-remove"></i></a>'+
                        '<a class="btn btn-mini" module="forms" action="settings" id="'+$(this).attr('idform')+'" href="#"><i class="icon-wrench"></i> Настройки</a>'+
                        '<a class="btn btn-mini" module="forms" action="edit" id="'+$(this).attr('idform')+'" href="#"><i class="icon-pencil"></i> Редактировать</a>'+
                    '</div></div>');
});
