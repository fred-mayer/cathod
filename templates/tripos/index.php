<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<? /* <? $this->setStyle("jquery-ui-1.10.2.custom.css"); ?> // Не работает переключатели bootstrap */ ?>
<?php if ( $this->auth->isAuthorized ){ ?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<? $this->setStyle("bootstrap.min.css"); ?>
	<? $this->setStyle("bootstrap-responsive.min.css"); ?>
	<script src="/templates/tripos/js/tinymce/tinymce.min.js"></script>
	<? $this->setScript("bootstrap.min.js"); ?>
<? } ?>
<? $this->getHeader(); ?>
<link href="/templates/tripos/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

<link rel="stylesheet" href="/templates/tripos/css/normalize.css">
<link rel="stylesheet" href="/templates/tripos/css/bootstrap.min.forms.css">
        <link rel="stylesheet" href="/templates/tripos/css/main.css">
        <script src="/templates/tripos/js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<?php $this->printAdminPanel() ?>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
        <?php if ( !$this->auth->isAuthorized ){ ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <?php } ?>
        <!-- fancybox галерея -->
        <script type="text/javascript" src="/templates/tripos/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
        <link rel="stylesheet" href="/templates/tripos/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/templates/tripos/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" href="/templates/tripos/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="/templates/tripos/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="/templates/tripos/js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet" href="/templates/tripos/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="/templates/tripos/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

        <script>window.jQuery || document.write('<script src="/templates/tripos/js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
        <script src="/templates/tripos/js/plugins.js"></script>
        <script src="/templates/tripos/js/main.js"></script>
        <?
        	//Вывод скриптов модулей
        	echo TPages::$script;
        ?>
</body>
</html>