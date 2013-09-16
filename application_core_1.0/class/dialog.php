<?php

class TDialog
{
    protected $title;
    protected $body;
    protected $btn_primary;
    protected $btn_next;
    protected $btn_url;

    //
    public function loadCurrentDialog( $dialog_dir, TTemplate $template, TModule $module )
    {
        if ( isset($template->get->dialog) ) // Загружаем диалог для конкретного модуля
        {
            if ( file_exists( $dialog_dir.$module->getName().'_'.$template->get->dialog.'.php' ) )
            {
                include_once( $dialog_dir.$module->getName().'_'.$template->get->dialog.'.php' );


                return true;
            }
        }
        else
            return false;
    }

    public function loadCurrentDialogByModule( $dialog_dir, TTemplate $template, TModule $module )
    {
        if ( isset($template->get->dialog) ) // Загружаем диалог для конкретного модуля
        {
            if ( file_exists( $dialog_dir.$template->get->dialog.'.php' ) )
            {
                include_once( $dialog_dir.$template->get->dialog.'.php' );


                return true;
            }
        }
        else
            return false;
    }

    public function setTitle( $title )
    {
        $this->title = $title;
    }

    public function setBody( $body )
    {
        $this->body = $body;
    }

    public function setNameButtonPrimary( $name, $url )
    {
        $this->btn_primary = $name;
        $this->btn_url = $url;
    }

    public function setButtonNext( $name, $url )
    {
        $this->btn_next = $name;
        $this->btn_url = $url;
    }

    public function displayDialog( $script=true )
    {
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo $this->title; ?></h3>
    </div>

    <div class="modal-body"><?php echo $this->body; ?></div>

<?php
        if ( isset($this->btn_primary) )
        {
?>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
        <button class="btn btn-primary"><?php echo $this->btn_primary; ?></button>
    </div>
<?php
            if ( $script===true )
            {
?>
    <script>
        $(".modal-footer .btn-primary").bind("click", function(e){
            
            if ( typeof d_start === 'function' )
            {
                d_start();
            }

            $.post('<?php echo $this->btn_url; ?>', d_get_post(), function(data) {
                    try {
                        d_complete();
                    } catch(e){
                        alert(data);
                        location.reload(); //пока можем только перезагрузить страницу(
                    }
                });
            
            $('#myModal').html( '<span class="loading"></span>' );

            e.preventDefault();
            return false;
        });
    </script>
<?php
            }elseif($script=="attach"){
                ?>
                    <script>
                        $(".modal-footer .btn-primary").bind("click", function(e){

                            var params = d_get_post();
                            
                            var xhr = new XMLHttpRequest();
                            xhr.open( "POST", "<?php echo $this->btn_url; ?>", true );
                            xhr.setRequestHeader("X-Requested-With", "XmlHttpRequest");
                            xhr.onreadystatechange = function(){

                                if ( this.readyState === 4 ) // запрос завершён
                                {
                                    if ( this.status === 200 ) 
                                        //d_complete(this.response);
                                        alert(this.response);
                                        location.reload(); //пока можем только перезагрузить страницу(
                                }
                            };
                            xhr.upload.addEventListener( "progress", function(e){

                                if ( e.lengthComputable )
                                {
                                    $("#progress").css("width",Math.round( e.loaded / e.total * 100 ) + "%");
                                }
                            });
                            xhr.send(params);
                            $("#process").show();

                            e.preventDefault();
                            return false;
                        });
                    </script>
                <?
            }
        }
    }

    /**
     * Функция urlAction возвращает url ajax для диалоговых окон
     * @param TTemplate $template
     * @param string $action
     * @param boolean $admin ссылка для админа?
     * @return string
     */
    public function urlAction( TTemplate $template, $action='', $admin=false )
    {
        return '/ajax/'.( $admin == true ? 'admin/' : '' ).$template->route[0].'?action='.( $action == '' ? $template->get->dialog : $action )
                .( isset($template->get->id) ? '&id='.$template->get->id : '' )
                .( isset($template->get->idpage) ? '&idpage='.$template->get->idpage : '' )
                .( isset($template->get->idmodule) ? '&idmodule='.$template->get->idmodule : '' )
                .( isset($template->get->set_pos) ? '&set_pos='.$template->get->set_pos : '' );
    }
}

?>