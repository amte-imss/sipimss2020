<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/img/template_sipimss/apple-icon.png'); ?>" />
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/template_sipimss/favicon.ico'); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>
            <?php echo (!is_null($title)) ? "{$title}&nbsp;|" : "" ?>
            <?php echo (!is_null($main_title)) ? $main_title : "SIPIMSS II" ?>
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <!-- BOOTSTRAP STYLES-->
        <?php echo css('bootstrap.css'); ?>
        <?php echo css('font-awesome.css'); ?>
        <?php //echo css('estilo_perfil.css'); ?>
        <!-- link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css' -->
        <?php //echo css('font-awesome.css'); ?>
        <?php //echo css('style.css'); ?>

        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Fichiers CSS -->
        <!-- link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/reset.css" -->
        <!--[if lt IE 9]>
                <link rel="stylesheet" href="css/cv.css" media="screen">
        <![endif]-->
        <script type="text/javascript">
            var site_url = "<?php echo site_url(); ?>";
        </script>
        <link rel="stylesheet" media="screen and (max-width:480px)" href="<?php echo base_url(); ?>assets/css/mobile.css">
        <link rel="stylesheet" media="screen and (min-width:481px)" href="<?php echo base_url(); ?>assets/css/cv.css">
        <link rel="stylesheet" media="print" href="<?php echo base_url(); ?>assets/css/print.css">
        <?php echo js("jquery.js"); ?>
        <?php echo js("jquery.min.js"); ?>
        <?php echo js("jquery.ui.min.js"); ?>
    </head>

    <body>
        <?php
        if (isset($main_content)) {
            ?>
            <div class="card-content">
                <?php
                echo $main_content;
                ?>
            </div>
        <?php } //fin content card      ?>
        <!-- Scripts JavaScript -->
        <!-- <script src="/jquery-1.js"></script> -->
        <!--[if lt IE 9]>
        <script src="scripts/placeholder.js"></script>
        <![endif]-->
        <footer >
            &copy; <a href="#" target="_blank">SIPIMSS 2017</a>
        </footer>
    </body></html>

