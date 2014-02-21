<?php
 $data = $this->data;
?>
<div class="catalog_tree" <?php if ( $template->auth->isAdmin ) echo ' idpage="'.$template->idpage.'" idmodule="'.$this->idmodule.'" set_pos="'.$this->set_pos.'"'; ?>>
<h4>Каталог</h4>
<? echo $data ?>
</div>
<?
    if ( $template->auth->isAuthorized ):
?>
<script>
    $(function() {
        $( ".catalog_tree[idmodule] ul.nav ul" ).sortable({
            update: function( event, ui ) {
                //alert(ui.item.html());
                var cats = new Array();
                $(ui.item).parent("ul").find("li").each(function() {
                    cats.push($(this).attr("idcat"));
                });
                $.post('/ajax/admin/catalog_tree?action=order&id=<? echo $this->idmodule ?>',{
                    'cats':cats.join(',')
                }, function(data) {
                    
                });
            }
        }); 
        $( ".catalog_tree[idmodule] ul.nav ul" ).disableSelection();
        $(".catalog_tree[idmodule] ul.nav ul > li a").prepend('<i class="icon-resize-vertical"></i>');
        $("i.action.delete").click(function(){
            if (confirm("Удалить категорию "+$(this).parent("li").find("a").text()+" и все вложенные подкатегории?")) {
                var id = $(this).parent("li").attr("idcat");
                var li = $(this).parent("li");
                $.post('/ajax/admin/catalog_tree?action=delCat&id=<? echo $this->idmodule ?>',{
                    'idcats':id
                }, function(data) {
                    li.remove();
                });
            } 
            
        });
        
    });
</script>
<?
    endif;
?>
