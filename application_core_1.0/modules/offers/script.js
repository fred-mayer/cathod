

//alert( $(".content[idcontent]").attr("idcontent") );

// блок категории
$(".block_offers[offercat]").each(function(){
	$(this).prepend('<div class="btn-toolbar"><div class="btn-group">'+
    '<a class="btn btn-mini" module="offers" model="category" action="add" id="'+$(this).attr('offercat')+'" href="#"><i class="icon-plus"></i> добавить предложение</a>'+
'</div></div>');
});

// блок предложения
$(".offer[idoffer]").hover(function(){
	$(this).addClass('admin-block');
},function(){
	$(this).removeClass('admin-block');
});
$(".offer[idoffer]").each(function(){
	$(this).prepend('<div class="btn-toolbar admin-modul-tools"><div class="btn-group">'+
    '<a class="btn btn-mini" module="offers" model="offer" action="edit" id="'+$(this).attr('idoffer')+'" href="#"><i class="icon-pencil"></i></a>'+
    '<a class="btn btn-mini" module="offers" model="offer" action="hide" id="'+$(this).attr('idoffer')+'" href="#"><i class="icon-eye-open"></i></a>'+
    '<a class="btn btn-mini" module="offers" model="offer" action="del" id="'+$(this).attr('idoffer')+'" href="#"><i class="icon-remove"></i></a>'+
'</div></div>');
});