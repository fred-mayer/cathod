<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<? $this->setStyle("bootstrap.min.css"); ?>
<? $this->setStyle("bootstrap-responsive.min.css"); ?>
<? $this->setStyle("template.css"); ?>
<? /* <? $this->setStyle("jquery-ui-1.10.2.custom.css"); ?> // Не работает переключатели bootstrap */ ?>
<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
<? $this->setScript("bootstrap.min.js"); ?>
<? $this->getHeader(); ?>
<?php if ( $this->auth->isAuthorized ){ ?>
 <script src="/templates/temp1/js/tinymce/tinymce.min.js"></script>
<? } ?>
<? /* <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script> // Не работает переключатели bootstrap */ ?>
<link href="/templates/temp1/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>
<body>
<?php $this->printAdminPanel() ?>

<?php if ( $this->issetPos('navTop')): ?>   
    <nav class="navbar <?php if ( !$this->displayTools ) echo ' navbar-fixed-top'; ?>">
        <div class="navbar-inner">
            <div class="container">
<?php       $this->getPos('navTop'); ?>
            </div>
        </div>
    </nav>
<?php endif; ?>
    <div class="container" <? if(!$this->displayTools ){ ?>style="margin-top:60px;"<? } ?>>
        <div class="row">
            <?php if ( $this->issetPos('header')): ?> 
            <header class="span12">
                <? $this->getPos('header'); ?>
            </header>
            <? endif; ?>
        </div>
        <div class="row">
            <?php if ( $this->issetPos('left')): ?>
            <aside class="span2 left">
                <? $this->getPos('left'); ?>
            </aside>
            <? endif; ?>
            <?php if ( $this->issetPos('section')): ?>
            <section class="span8">
                <? $this->getPos('section'); ?>
            </section>
            <? endif; ?>
            <?php if ( $this->issetPos('right')): ?>
            <aside class="span2 right">
                <? $this->getPos('right'); ?>
            </aside>
            <? endif ?>
        </div>
    </div>
    <footer>
       <div class="container">
           <? $this->getPos('footer'); ?>
       </div>
    </footer>
</body>
</html>