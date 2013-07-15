<?php

    $data = $this->getData();

    if ( isset($data['twitter']) )
    {
?>
       <!--  <a href="/profile">Список пользователей</a>-->
        </br>
        <a href="/">На главную</a>
        <br>
        <div class="conteiner row">
            <div class="avatar-profile">
                <img src="/media/profile/<?php echo $data['profile']->img; ?>">
            </div>
        <h2><?php echo $data['profile']->name; ?></h2>
        <h3><a href=" http://twitter.com/<?php echo $data['profile']->nickname; ?>" target="_blank">@<?php echo $data['profile']->nickname; ?></a></h3>
<?php

        if ( $this->auth->isAdmin && !$this->template->isPreview )
        {
?>
        <a class="btn btn-mini" module="twitter_profile" action="addpost" idprofile="<? echo $data['profile']->id; ?>" href="#"><i class="icon-plus"></i>Добавить пост</a>
        <script>
        $(".btn[action=addpost]").bind("click", function(e){

            $('#myModal').html( '<span class="loading"></span>' );
            $('#myModal').modal( 'show' );

            $('#myModal').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')+'&idprofile='+$(this).attr('idprofile') );
            e.preventDefault();
            return false;
        });
        </script>
<?php
        }
?>
        <ul id="twitter">
<?php
        foreach ( $data['twitter'] as $row )
        {
?>
            <li class="message_text">
            <img src="/media/images/point_2.png" alt="">
<?php
                if ( $this->auth->isAdmin && !$this->template->isPreview )
                {
?>
                <div class="btn-toolbar">
                    <div class="btn-group">
                        <a class="btn btn-mini" module="twitter_profile" action="editpost" idpost="<?php echo $row->id; ?>" href="#"><i class="icon-pencil"></i></a>
                        <a class="btn btn-mini" module="twitter_profile" action="delpost" idpost="<?php echo $row->id; ?>" href="#"><i class="icon-remove"></i></a>
                    </div>
                </div>
<?php
                }
?>
                <p><?php echo $row->post; ?></p>
             
            </li>
<?php
        }
?>
        </ul>
<?php
        

        if ( $this->auth->isAdmin && !$this->template->isPreview )
        {
?>
        <script>
        $(".btn[action=editpost]").bind("click", function(e){

            $('#myModal').html( '<span class="loading"></span>' );
            $('#myModal').modal( 'show' );

            $('#myModal').load( '/ajax/admin/'+$(this).attr('module')+'?dialog='+$(this).attr('action')+'&idpost='+$(this).attr('idpost') );
            
            e.preventDefault();
            return false;
        });
        
        $(".btn[action=delpost]").bind("click", function(e){

            $('#myModal').html( '<span class="loading"></span>' );
            $('#myModal').modal( 'show' );

            var self = this;
        
            $.post( '/ajax/admin/'+$(this).attr('module')+'?action='+$(this).attr('action')+'&id='+$(this).attr('idpost'), function(data){

                //alert(data);
                var e = $(self).parent().parent().parent();
                e.remove();

                $('#myModal').modal( 'hide' );
            });
            
            e.preventDefault();
            return false;
        });
        </script>
<?php
        }
    }
    else
    {
?>
        <ul id="profile">
<?php
        foreach ( $data['profile'] as $row )
        {
?>
            <li>
                <div class="avatar-profile">
                    <img src="/media/profile/<?php echo $row->img; ?>">
                </div>
                
                <a href="/profile/<?php echo $row->nickname; ?>"><?php echo $row->name; ?></a>
            </li>
<?php
        }
?>
        </ul>
<?php
    }

?>