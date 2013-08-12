<?php
$tree = $module->admin->getTree("-");
$this->setTitle( 'Добавление категории в интернет-магазин' );
$form = new TForm();
    $form->beginForm();
    $form->inputText( 'name',"Название категории");
    $form->inputText( 'alias',"Alias категории");
    $form->select('parent',"Родительская категория",$tree);
    $form->endForm();
$this->setBody( $form );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template,'',true ) );
$this->displayDialog();
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        location.reload();
        //var idcat = $("#parent").val()
        //$(".admin-module[idmodule=<?php echo $template->get->id; ?>]").find("li[idcat]="+idcat).append("<li>123</li>");
    };
</script>