<?php

    $this->setTitle( 'Уверины что хотите скрыть модуль?' );
    
    $form = new TForm();
    $form->beginForm();
    $form->checkbox( 'pages', 'скрыть модуль на всех страницах', 'all' );
    $form->endForm();

    $this->setBody( $form );
    $this->setNameButtonPrimary( 'Скрыть', $this->urlAction( $template ) );
    $this->displayDialog();

?>
<script>
    function d_complete()
    {
        $('#myModal').modal( 'hide' );
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>']").addClass('unpublished');
        
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>'] a[action='hidemodule']").html('<i class="icon-eye-open"></i>');
        $(".admin-module[idmodule='<?php echo $template->get->idmodule; ?>'][set_pos='<?php echo $template->get->set_pos; ?>'] a[action='hidemodule']").attr('action', 'showmodule');
    };
</script>
