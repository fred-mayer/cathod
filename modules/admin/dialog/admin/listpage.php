<?php

    $data = $module->admin->getAllPages();

    $this->setTitle( 'Список страниц' );
    ob_start();
?>

        <div class="control-group">
            <div class="controls clearfix">
                <button class="btn" module="admin" action="newpage">Новая страница</button>
            </div>
        </div>

    <table class="table table-bordered">
        <tr>
            <td>Название</td>
            <td>Псевдоним</td>
            <td>Шаблон</td>
            <td>Активна</td>
            <td></td>
        </tr>
<?php

    foreach ( $data as $row )
    {
        
        if ( $row->id_parent > 0)
        {
            $p = $module->admin->getPagesById( $row->id_parent );
            $row->alias = $p->alias.'/'.$row->alias;
        }
?>
        <tr>
            <td><a href="<?php echo DS.$row->alias.DS; ?>" target="_blank"><? echo $row->title; ?></a></td>
            <td><? echo $row->alias; ?></td>
            <td><? echo $row->template; ?></td>
            <td><? echo $row->hide == '' ? 'да' : 'нет'; ?></td>
            <td>
                <a href="" class="btn btn-mini" module="admin" action="editpage" idpage="<? echo $row->id; ?>" title="Редактировать страницу"><i class="icon-pencil"></i></a>
                <a href="" class="btn btn-mini" module="admin" action="copypage" idpage="<? echo $row->id; ?>" title="Клонировать страницу"><i class="icon-share"></i></a>
            </td>
        </tr>
<?php
    }

?>
    </table>
<?php

    $html = ob_get_contents();
    ob_end_clean();
    
    $this->setBody( $html );
    $this->displayDialog();
    
?>
<script>
    $(".btn[module=admin]").bind("click", function(e){

        $('#myModal').html( '<span class="loading"></span>' );
        $('#myModal').modal( 'show' );

        $('#myModal').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')
                    +(( $(this).attr('id') !== undefined ) ? '&id='+$(this).attr('id') : '')
                    +(( $(this).attr('idpage') !== undefined ) ? '&idpage='+$(this).attr('idpage') : '')
                    +(( $(this).attr('idmodule') !== undefined ) ? '&idmodule='+$(this).attr('idmodule') : '')
                    +(( $(this).attr('set_pos') !== undefined ) ? '&set_pos='+$(this).attr('set_pos') : '')
                    +(( $(this).attr('more') !== undefined ) ? $(this).attr('more') : ''));
        e.preventDefault();
        return false;
    });
</script>