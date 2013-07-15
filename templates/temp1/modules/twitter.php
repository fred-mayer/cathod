<?php

    $data = $this->getData();

    if ( $template->route[0] == 'post' )
    {
?>
        <a href="/">На главную</a>
        <br><br>
<?php
    }
?>
<div id="twitter" class="message"<?php if ( $template->route[0] == 'post' ) echo ' style="text-align: center;"'; ?>>
    <blockquote>
        <p id="twitter_message" style="cursor:pointer;<?php if ( $template->route[0] == 'post' ) echo 'text-align: center;'; ?>">&laquo;<?php echo strip_tags($data->post); ?>&raquo;</p>

        <div class="blockquote_small">
            <div class="avatar">
                <img src="/media/profile/<?php echo $data->img; ?>">
            </div>
            <span class="small"><?php echo $data->name; ?> <a href="/profile/<?php echo $data->nickname; ?>">@<?php echo $data->nickname; ?></a></span>
        </div>
    </blockquote>
    
    <script>
        var timeout_id;
        $("#twitter_message").bind("click", function(e){
            
            if (timeout_id !== undefined) clearTimeout(timeout_id);

            $('#twitter').fadeOut('slow', function(){

                $.post( '/ajax/twitter?action=getRandPost', {}, function( data ){

                    $('#twitter').html( data );
                    $('#twitter').fadeIn('slow');
                });
            });

            e.preventDefault();
            return false;
        });
    </script>
<?php

    $twitter_text = strip_tags($data->post);
    
    $str = new TString( $twitter_text );
    $str2 = new TString( 'http://'.$_SERVER["SERVER_NAME"].'/post/'.$data->idpost.' #чикенхиро' );
    $l = 140 - count( $str2 );
    $twitter_text = (count( $str ) > $l) ? $str->substr( $l-2 ).'...' : $twitter_text;
    
    $twitter_text = '«'.$twitter_text.'»';

?>
    <div class="share_buttons">
        <a onClick="window.open('https://twitter.com/intent/tweet?hashtags=чикенхиро&original_referer=<?php echo 'http://'.$_SERVER["SERVER_NAME"].'/post/'.$data->idpost; ?>&text=<?php echo $twitter_text; ?>&tw_p=tweetbutton&url=<?php echo 'http://'.$_SERVER["SERVER_NAME"].'/post/'.$data->idpost; ?>', 'twitter', 'toolbar=0,status=0,width=548,height=325');" target="_parent" href="javascript: void(0);" class="tweetbutton"></a>
        <a onClick="window.open('http://vkontakte.ru/share.php?url=<?php echo 'http://'.$_SERVER["SERVER_NAME"].'/post/'.$data->idpost; ?>&title=Кисы решают&description=<?php echo '«'.strip_tags($data->post).'»'; ?>&image=<?php echo 'http://'.$_SERVER["SERVER_NAME"].'/media/profile/'.$data->img; ?>&noparse=true', 'vk', 'toolbar=0,status=0,width=548,height=325');" target="_parent" href="javascript: void(0);" class="vkbutton"></a>
    </div>

</div>
<?php

    if ( !($template->route[0] == 'post' && $this->set_pos == 'section') )
    {
?>
<script>
    timeout_id = setTimeout( function(){
        
        $.post( '/ajax/twitter?action=getRandPost', {}, function( data ){
            
            $('#twitter').fadeOut('slow', function(){
                
                $('#twitter').html( data );
                $('#twitter').fadeIn('slow');
            });
        });
    }, 10000);
</script>
<?php
    }
?>