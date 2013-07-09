<?
//Контроллер страницы page1


$template->setTitle( 'CMS-Vitrina - Главная страница' );

$template->setStyle( 'style.css' );

//модуль выборки предложений по категориям
//category - параметр категории или названии таблиц если значение опущено тогда берет uri параметр 1
//bank - параметр уточнения предложений по банку, если параметр category опущен тогда берет uri параметр 2
/*$moffers = $module->getModule( 'offers', array('category'=>'kredits','bank'=>'bank-moskvi','isFormBut'=>0) ); // возвращает класс расширенного модуля
$template->setPos( 'section', $moffers );*/

$contentNavTop = $module->getModule( 'content', array('id'=>4));
$template->setPos( 'navTop', $contentNavTop );

$moffers2 = $module->getModule( 'offers', array('isMain'=>1,'isFilter'=>1)); // возвращает класс расширенного модуля
$template->setPos( 'iframe', $moffers2 );

//$offerD = $module->getModule( 'offer_detail', array('category'=>'kredits','id'=>1));
//$template->setPos( 'section', $offerD );

?>