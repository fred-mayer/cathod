<?php

/*
 * Шаблон диалога настроек для модуля статей
 * 
 * @author Fred Mayer
 */
$params = (array) json_decode($module->getParams());
$moduleInfo = $module->getModuleInfo();
$cats = $module->admin->getCats();
$this->setTitle( 'Настройки модуля' );
$form = new TForm();
$form->beginForm();

$form->html('
        <h4>Основные настройки</h4>
');
$form->inputText("name","Название модуля",$moduleInfo->title);
$options = array();
for($i=0;$i<count($cats);$i++){
    $options[$i]['title'] = $cats[$i]->name;
    $options[$i]['value'] = $cats[$i]->id;
}
$form->select("cat","Категория",$options,$params['idcat']);
$form->inputText("newcat","Создать новую");
$form->html('
    <h4>Дополнительные настройки</h4>
');
$form->inputText("counts","Кол-во новостей",isset($params['counts'])? $params['counts']:0);
$form->inputText("cols","Кол-во колонок",isset($params['cols'])? $params['cols']:"default");
$form->inputText("template","Шаблон",isset($params['template'])? $params['template']:"default");
$form->inputText("mainlink","alias ссылка",isset($params['mainlink'])? $params['mainlink']:"");
$form->inputText("sfx","Суффикс модуля",isset($params['sfx'])? $params['sfx']:"");

$form->endForm();
$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction($template, '', true ) );
$this->displayDialog();
?>
<script>
    function d_complete(){
        $('#myModal').modal( 'hide' );
        location.reload;
    }
</script>