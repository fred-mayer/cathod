<?php

    $data = $this->getData();
    
    
    if ( $data['url_form'] != '' )
    {
/*<script type="text/javascript"> 
function resizeIframe(newHeight)
{
    document.getElementById('blogIframe').style.height = parseInt(newHeight,10) + 10 + 'px';
}onload='parent.resizeIframe(document.body.scrollHeight)' id="blogIframe"
</script>  */
?>
    <iframe src="<?php echo $data['url_form']; ?>" width="100%" height="100%" frameborder="0" align="left"></iframe>
<?php
    }
    else
    {
        $this->beginForm();

        foreach ( $data['input'] as $row )
        {
            $this->getInput( $row );
        }

        $this->endForm();
    }
    
?>