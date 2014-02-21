<?php

    $this->setTitle( 'Показать модуль?' );
    
    $form = new TForm();
    $form->beginForm();
    $form->checkbox( 'pages', 'показать модуль на всех страницах', 'all' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Показать', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>']").removeClass('unpublished');
        
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>'] a[action='showmodule']").html('<i class="icon-eye-close"></i>');
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>'] a[action='showmodule']").attr('action', 'hidemodule');
    };
</script>
