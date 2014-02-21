<?php
    $cats = $newmodule->admin->getCats();
    $options = array();
    for($i=0;$i<count($cats);$i++){
        $options[$i]['title'] = $cats[$i]->name;
        $options[$i]['value'] = $cats[$i]->id;
    }
    $form->select("cat","Выберите категорию",$options);
    $form->inputText("newcat","...или создать новую");
?>