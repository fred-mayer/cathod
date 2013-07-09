

$(".content[idcontent]").each(function(){
    $(this).prepend('<div class="btn-toolbar"><div class="btn-group">'+
                        '<a class="btn btn-mini" module="content" model="content" action="edit" id="'+$(this).attr('idcontent')+'" href="#"><i class="icon-pencil"></i></a>'+
                        '<a class="btn btn-mini" module="content" model="content" action="hide" id="'+$(this).attr('idcontent')+'" href="#"><i class="icon-eye-open"></i></a>'+
                        '<a class="btn btn-mini" module="admin" action="delmodule" idpage="'+$(this).attr('idpage')+'" idmodule="'+$(this).attr('idmodule')+'" set_pos="'+$(this).attr('set_pos')+'" href="#"><i class="icon-remove"></i></a>'+
                    '</div></div>');
});
