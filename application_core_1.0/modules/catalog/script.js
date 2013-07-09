

$(".catalog[idmodule]").each(function(){
    $(this).prepend('<div class="btn-toolbar"><div class="btn-group">'+
			'<a class="btn btn-mini" module="catalog" model="catalog" action="addMagazine" id="'+$(this).attr('idmodule')+'" href="#" title="Добавить магазин для парсинга товаров"><i class="icon-shopping-cart"></i></a>'+
                        '<a class="btn btn-mini" module="catalog" model="catalog" action="magazineCats" id="'+$(this).attr('idmodule')+'" href="#" title="Страницы для парсинга"><i class="icon-tasks"></i></a>'+
			'<a class="btn btn-mini" module="catalog" model="catalog" action="parse" id="'+$(this).attr('idmodule')+'" href="#" title="Обновить товары с магазинов"><i class="icon-refresh"></i></a>'+
                        '<a class="btn btn-mini" module="catalog" model="catalog" action="del" id="'+$(this).attr('idmodule')+'" href="#" title="Удалить модуль со страницы"><i class="icon-remove"></i></a>'+
                    '</div></div>');
});

$(".catalog[idmodule] .product").each(function(){
    $(this).prepend('<div class="btn-toolbar"><div class="btn-group">'+
			'<a class="btn btn-mini" module="catalog" model="catalog" action="editItem" id="'+$(this).attr('idproduct')+'" href="#" title="Редактировать товар"><i class="icon-pencil"></i></a>'+
                        '<a class="btn btn-mini" module="catalog" model="catalog" action="delItem" id="'+$(this).attr('idproduct')+'" href="#" title="Удалить"><i class="icon-remove"></i></a>'+
                    '</div></div>');
});
