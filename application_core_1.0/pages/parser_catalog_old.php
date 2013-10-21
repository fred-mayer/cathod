<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">
        <script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
        <style>
            label{display:block;}
            label, textarea{display:block;}
            textarea{
                width:100%;
                height:250px;
                -webkit-box-sizing:border-box;
                -moz-box-sizing:border-box;
                box-sizing:border-box;
            }
        </style>
    </head>
    <body>
<?php

    if ( isset($template->get->skey) && $template->get->skey == '1q2w3e4r5t' )
    {
        $form = new TForm();
        $form->beginForm( 'form_parser' );

        $arr = $template->db->select( 'SELECT m.id AS value, m.name AS title FROM catalog_magazine m WHERE hide=1' )->toObject()->toArray();
        $arr = array_merge( array(array('value'=>0, 'title'=>'--Все' )), $arr );
        $form->select( 'mag', 'Магазины', $arr );

        $arr1 = $template->db->select( 'SELECT * FROM catalog_cats c WHERE hide=1 AND parentid=0' )->toObject();
        $arr2 = $template->db->select( 'SELECT * FROM catalog_cats c WHERE hide=1 AND parentid>0' )->toObject();

        foreach ( $arr1 as $value1 )
        {
            if ( $value1->parentid == 0 )
            {
                $cat[] = array('value'=>$value1->id, 'title'=>$value1->name );

                foreach ( $arr2 as $value2 )
                {
                    if ( $value2->parentid == $value1->id )
                    {
                        $cat[] = array('value'=>$value2->id, 'title'=>'-'.$value2->name );
                    }
                }
            }
        }

        $arr = array_merge( array(array('value'=>0, 'title'=>'--Все' )), $cat );
        $form->select( 'cat', 'Категории', $arr );

        $form->submit( 'Старт' );
        $form->button( 'stop', 'Стоп' );
        
        $form->html( '<p id="curr-post"></p>' );
        
        $form->textarea('log', 'log');

        $form->endForm();


        ob_start();
    
?>
<script>
    
    var arr_mag_id = [];
    var arr_cat_id = [];
    var next_mag_id = 0, next_cat_id = 0;
    var cur_mag_id = 0, cur_cat_id = 0;
    var status = 0;
    
    $("#stop").attr('disabled','disabled');
        
    $("#form_parser").bind("submit", function(e){

        status = 1;

        $("#mag").attr('disabled','disabled');
        $("#cat").attr('disabled','disabled');
        $("#form_parser button[type=submit]").attr('disabled','disabled');
        $("#stop").removeAttr('disabled');
        
        var mag_id = $('#mag option:selected').val();
        var cat_id = $('#cat option:selected').val();
        
        if ( mag_id === '0' )
        {
            $('#mag option').each(function(){
                
                var val = $(this).val();
                if ( val !== '0' ) arr_mag_id.push( $(this).val() );
            });
        }
        else
        {
            arr_mag_id.push( mag_id );
        }
        
        if ( cat_id === '0' )
        {
            $('#cat option').each(function(){
                
                var val = $(this).val();
                if ( val !== '0' ) arr_cat_id.push( $(this).val() );
            });
        }
        else
        {
            arr_cat_id.push( cat_id );
        }
        
        //alert('submit mag_id - '+mag_id+' cat_id - '+cat_id);
        //alert(arr_mag_id);
        //alert(arr_cat_id);
        
        next_arr_mag();
        post();
        
        e.preventDefault();
        return false;
    });
    $("#stop").bind("click", function(e){
    
        stop();
        e.preventDefault();
        return false;
    });
    function next_arr_mag()
    {
        if ( arr_mag_id.length === next_mag_id ) return false;
        cur_mag_id = arr_mag_id[next_mag_id];
        next_mag_id++;
        return true;
    }
    function next_arr_cat()
    {
        if ( arr_cat_id.length === next_cat_id ) return false;
        cur_cat_id = arr_cat_id[next_cat_id];
        next_cat_id++;
        return true;
    }
    function post()
    {
        if ( status == 0 ) return;

        if ( !next_arr_cat() )
        {
            if ( !next_arr_mag() )
            {
                $("#mag").removeAttr('disabled');
                $("#cat").removeAttr('disabled');
                $("#form_parser button[type=submit]").removeAttr('disabled');
                $("#stop").attr('disabled','disabled');
                return;
            }
            
            next_cat_id = 0;
        }
        
        $("#curr-post").text( $("#mag option[value="+cur_mag_id+"]").text()+" -> "+$("#cat option[value="+cur_cat_id+"]").text() );
        
        $.post("/ajax/catalog?action=parser&skey=1q2w3e4r5t", { mag_id: cur_mag_id, cat_id: cur_cat_id }, function(data){
            
            //alert("Data Loaded: "+data);
            $("#log").val( $("#log").val()+data );
            
            post();
        });
    };
    function stop()
    {
        status = 0;
        $("#mag").removeAttr('disabled');
        $("#cat").removeAttr('disabled');
        $("#form_parser button[type=submit]").removeAttr('disabled');
        $("#stop").attr('disabled','disabled');
        
    }
    function d_complete(data)
    {
        
    };
</script>
<?php

        $script = ob_get_contents();
        $form = $form.$script;
        ob_end_clean();

        echo $form;
        //$template->setPosHTML( 'section', $form );
    }
?>
    </body>
</html>
<?php
//var_dump($_POST);
//http://localhost:8888/parser_catalog?skey=1q2w3e4r5t&id_mag=1&id_cat=22
    /*set_time_limit( 14400 );

    $catalog = $template->getModule( 'catalog' );

    if ( isset($template->get->skey) && $template->get->skey == '1q2w3e4r5t' )
    {
        $catalog->parser( $template->get, $template->get );
    }*/

    exit();

?>