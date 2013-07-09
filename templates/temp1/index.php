<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<? $this->setStyle("bootstrap.css"); ?>
<? $this->setStyle("bootstrap-responsive.css"); ?>
<? $this->setStyle("template.css"); ?>
<? $this->setStyle("jquery-ui-1.10.2.custom.css"); ?>
<?php /*<script src="http://code.jquery.com/jquery.js"></script> */ ?>
<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
<? $this->setScript("bootstrap.min.js"); ?>
<? $this->getHeader(); ?>
 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<?php if ( $this->auth->isAuthorized ){ ?>
 <script src="/templates/temp1/js/tinymce/tinymce.min.js"></script>
 <script type="text/javascript" src="/media/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
 <script type="text/javascript" src="/media/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
 <link rel="stylesheet" type="text/css" href="/media/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<? } ?>
</head>
<body>
<?php

    if ( $this->auth->isAuthorized )
    {
?>
    <div id="admin-panel">
        <p>Админ панель</p>
        <div class="btn-toolbar-admin">
            <div class="btn-group">
                <a href="?preview" class="btn">Предварительный просмотр</a>
                <button class="btn" module="admin" action="newpage">Новая страница</button>
                <button class="btn" module="admin" action="editpage" idpage="<? echo $this->idpage; ?>">Редактировать страницу</button>
                <button class="btn" module="admin" action="copypage" idpage="<? echo $this->idpage; ?>">Клонировать страницу</button>
            </div>
        </div>
    </div>
    <div id="admin-sub-panel">
        <span id="shText" class="btn btn-danger">Админ</span>
    </div>    
<?php
    }

?>
    <div class="modal" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;"></div>
<?php

    if ( $this->issetPos('iframe') )
    {
        $this->getPos('iframe');
    }


    if ( $this->issetPos('navTop') || $this->auth->isAuthorized)
    {
?>
    <nav class="navbar navbar-inverse<?php if ( !$this->auth->isAuthorized ) echo ' navbar-fixed-top'; ?>">
        <div class="navbar-inner">
            <div class="container">
<?php
                $this->getPos('navTop');
                if ( $this->auth->isAuthorized ){ ?>
<a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="navTop" href="#"><i class="icon-plus"></i></a>
                <?php } ?>
            </div>
        </div>
    </nav>
<?php
    }

?>
    <div class="container" <? if(!$this->auth->isAuthorized ){ ?>style="margin-top:60px;"<? } ?>>
        <div class="row">
        <div class="span10">
<?php

        if ( $this->issetPos('header') || $this->auth->isAuthorized )
        {
?>
        
        <header class="<?php if ( $this->auth->isAdmin && !$this->isPreview ) echo ' admin-module'; ?>">
<?php
            $this->getPos('header');
            
            if ( $this->auth->isAdmin && !$this->isPreview )
            {
?>
            <a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="header" href="#"><i class="icon-plus"></i></a>
<?php
            }
?>
        <hr/>
        </header>
<?php
        }

?>
        <div class="row">
<?php

        if ( $this->issetPos('left') || $this->auth->isAuthorized )
        {
?>
        <aside class="left span2<?php if ( $this->auth->isAdmin && !$this->isPreview ) echo ' admin-module'; ?>">
<?php
            if ( $this->issetPos('left') || $this->auth->isAuthorized )
            {

                $this->getPos('left');

                if ( $this->auth->isAdmin && !$this->isPreview )
                { ?>
                <a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="left" href="#"><i class="icon-plus"></i></a>
<?php           }
            } ?>
        </aside>
<?php } ?>
<?php
        if ( $this->issetPos('section') || $this->auth->isAuthorized )
        {
?>
        <section class="span8<?php if ( $this->auth->isAdmin && !$this->isPreview ) echo ' admin-module'; ?>">
<?php
            $this->getPos('section');              
            if ( $this->auth->isAdmin && !$this->isPreview )
            {
?>
            <a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="section" href="#"><i class="icon-plus"></i></a>
<?php
            }
?>
        </section>
        
<?php
        }

?>
    </div></div>
    <?php
        if ( $this->issetPos('aside') || $this->auth->isAuthorized )
        {
?>
        <aside class="right span2<?php if ( $this->auth->isAdmin && !$this->isPreview ) echo ' admin-module'; ?>">
<?php
            if ( $this->issetPos('aside') || $this->auth->isAuthorized )
            {

                $this->getPos('aside');

                if ( $this->auth->isAdmin && !$this->isPreview )
                { ?>
                <a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="aside" href="#"><i class="icon-plus"></i></a>
<?php           }
            } ?>
        </aside>
<?php } ?>
<?php

        if ( $this->issetPos('footer') || $this->auth->isAuthorized )
        {
?>
        <footer<?php if ( $this->auth->isAdmin && !$this->isPreview ) echo ' class="admin-module"'; ?>>
<?php
            $this->getPos('footer');

            if ( $this->auth->isAdmin && !$this->isPreview )
            {
?>
            <a class="btn btn-mini" module="admin" action="addmodule" idpage="<? echo $this->idpage; ?>" set_pos="footer" href="#"><i class="icon-plus"></i></a>
<?php
            }
?>
        </footer>
<?php
        }

?>
        </div>
    </div>
</body>
</html>