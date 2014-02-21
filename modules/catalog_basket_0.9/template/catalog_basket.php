<?
$data = $this->getData();
$count = $data['count'];
$summ = $data['summ'];
?>
<aside class="basket_module">
    <div class="body">
        <p><strong><span class="count_items"><? echo $count ?></span> товар<?
            if($count<5 && $count>1){
                echo 'а';
            }elseif($count>=5 || $count==0){
                echo 'ов';
            }
        ?> | <span class="total_price"><? echo $summ ?></span> руб.</strong></p>
        <a href="/catalog/basket/">Оформить покупки</a>
    </div>
</aside>
<script>
(function($){
   jQuery.fn.updateBasket = function(options){
       var options = $.extend({
            countItems: 0,
            totalPrice: 0
        }, options);
        
        var make = function(){
            if(options.countItems>0 && options.totalPrice>0){
                $(this).find(".count_items").text(options.countItems);
                $(this).find(".total_price").text(options.totalPrice);
            }else{
                var totalPriceEl = $(this).find(".total_price");
                var countItemsEl = $(this).find(".count_items");
                $.getJSON("/ajax/catalog_basket?action=getBasketJson",function(data){
                    if(data['result']==='ok'){
                        countItemsEl.text(data['count']);
                        totalPriceEl.text(data['sum']);
                    }else{
                        countItemsEl.text(0);
                        totalPriceEl.text(0);
                    }
                });
            }
        };
        
        return this.each(make); 
   };
})(jQuery); 
</script>