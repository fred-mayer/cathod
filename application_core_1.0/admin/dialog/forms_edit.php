<?php
$formSite = $module->admin->getFormSettings($template->get->id);
$this->setTitle( 'Редактировать форму'. $formSite->name );

ob_start();
?>
<div class="contanier_drag_drop">
    <aside class="toolbar_forms">
        <h4>Добавить</h4>
        <ul>
            <li><a href="javascript:void(0)" typeField="inputText" draggable="true">inputText</a></li>
            <li><a href="javascript:void(0)" typeField="select" draggable="true">select</a></li>
        </ul>
    </aside>
    <div class="dd">
        
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
                $('.dd').on({
                    dragenter: function(e) {
                        $(this).css('background-color', 'lightBlue');
                    },
                    dragleave: function(e) {
                        $(this).css('background-color', 'white');
                        alert(333);
                    },
                    drop: function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        console.log(e.dataTransfer.files);
                        alert(123);
                    },
                    dragend: function(e){
                        alert(123);
                    }
                });
                $('.toolbar_forms a').on({
                    drop: function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        //console.log(e.dataTransfer.files);
                        alert(123);
                    }
                });
    });
</script>
<?
$res = ob_get_contents();
ob_end_clean();
$this->setBody( $res );
$this->setNameButtonPrimary( 'Сохранить', $this->urlAction( $template ) );
    $this->displayDialog();
?>
