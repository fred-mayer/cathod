<?php
$tree = $module->admin->getTree("-");
$mcs = $module->admin->getMagCats($tree);
$form = new TForm();
$form->beginForm();
foreach($mcs as $ms){
    $form->html("<h3>".$ms['title']."</h3>");
    foreach($ms['mag'] as $mags){
        $form->inputText( 'url_'.$ms['catid'].'_'.$mags['id'],$mags['name'],((isset($mags['url']))? $mags['url']:''));
    }
}
$form->endForm();

$this->setBody( '<div class="small">'.$form.'</div>' );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template,'',true ) );
$this->displayDialog();
?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
    };
</script>
