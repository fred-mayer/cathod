<?php

    $modules = $module->getModules();

    $this->setTitle( 'Добавить новый модуль' );
    ob_start();
?>
    <form class="form-horizontal">
    	<div class="control-group">
            <label class="control-label" for="modules_new">Модуль:</label>
            <div class="controls">
                <select id="modules_new">
<?php
                    foreach ( $modules as $m )
                    {
?>
                    <option value="<? echo $m->id; ?>"><? echo $m->title; ?></option>
<?php
                    }
?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="pages_new">Добавить:</label>
            <div class="controls">
                <select id="pages_new">
                    <option value="current">на текущую страницу</option>
                    <option value="all">на все страници</option>
                </select>
            </div>
        </div>
    </form>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    
    $this->setBody( $html );
    $this->setNameButtonPrimary( 'Дальше', '' );
    $this->displayDialog( false );
?>
<script>
    function d_get_post()
    {
        
    }
    function d_complete()
    {
        
    }
    $(".modal-footer .btn-primary").bind("click", function(e){

        //var exist_module = $('#modules option:selected').attr('exist');
        var id = $('#modules_new option:selected').val();
        var pages = $('#pages_new option:selected').val();
            
        /*if ( exist_module === '1' ) // существует
        {

            $.post('/ajax/admin/admin?action=addmodule&idpage=<? echo $template->get->idpage;?>&idmodule='+id+'&set_pos=<? echo $template->get->set_pos;?>'+'&pages='+pages, function(data){

                $('#myModal').modal( 'hide' );
                $(data).insertBefore( "a[set_pos=<? echo $template->get->set_pos;?>]" );
            });
        }
        else // нет
        */
        //{
            //var current_tab = d_loading();//$('#myModal').html( '<span class="loading"></span>' );
            
                //alert(current_tab.attr('class'));
            dialog.load( '/ajax/admin/admin?dialog=newmodule&idpage=<? echo $template->get->idpage;?>&idmodule='+id+'&set_pos=<? echo $template->get->set_pos;?>'+'&pages='+pages);
            //current_tab.load( '/ajax/admin/admin?dialog=newmodule&idpage=<? echo $template->get->idpage;?>&idmodule='+id+'&set_pos=<? echo $template->get->set_pos;?>'+'&pages='+pages);
            //$('#myModal').load( '/ajax/admin/admin?dialog=newmodule&idpage=<? echo $template->get->idpage;?>&idmodule='+id+'&set_pos=<? echo $template->get->set_pos;?>'+'&pages='+pages);
        //}

        e.preventDefault();
        return false;
    });
    
    
    function d_loading()
    {
        $('#myModal .modal-tab').hide();
        $('#myModal .modal-tab').after( '<div class="modal-tab"><span class="loading"></span></div>' );
        
        return $('#myModal .modal-tab .loading').parent();
    }
</script>