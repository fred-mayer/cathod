<?php

class Tlogin extends TModule
{
    public function display( TTemplate $template )
    {
        $this->data = $this->getParams();

        parent::display( $template );
    }
}

?>