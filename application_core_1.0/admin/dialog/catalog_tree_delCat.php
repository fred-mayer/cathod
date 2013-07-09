<?php
$tree = $module->admin->getTree("-");
$this->setTitle( 'Удаление категорий с интернет магазина' );
$form = new TForm();
    $form->beginForm();
    foreach($tree as $cat){
       $form->checkbox("del[]",$cat['title'],$cat['value']); 
    }
    $form->endForm();
$this->setBody( $form );
$this->setNameButtonPrimary( 'Удалить', $this->urlAction( $template ) );
$this->displayDialog();
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        
    };
</script>