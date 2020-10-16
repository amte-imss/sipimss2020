<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($texts["title"]) ? $texts["title"] . "::" : ""; ?> SIPIMSS</title>
    <link rel="shortcut icon" href="http://educacionensalud.imss.gob.mx/sites/all/themes/ces/favicon.ico" type="image/vnd.microsoft.icon">
    <meta name="description" content="Censo de docentes Coordinación de Educación en Salud">
    <?php echo css('bootstrap.css'); ?>
    <?php echo css('style_login.css'); ?>
    <script type="text/javascript">
    var url = "<?php echo base_url(); ?>";
    var site_url = "<?php echo site_url(); ?>";
    var img_url_loader = "<?php echo base_url('assets/img/loader.gif'); ?>";
    </script>
    <?php echo js("jquery.js"); ?>
    <?php echo js("jquery.min.js"); ?>
    <?php echo js("jquery.ui.min.js"); ?>
    <?php echo js('template_sipimss/general.js'); ?>
    <?php echo js("login.js"); ?>
    <?php echo js("bootstrap.js"); ?>
    <?php echo js('captcha.js'); ?>
    <!-- Google Analytics -->
    <script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
        m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-109411950-1', 'auto');
    ga('send', 'pageview');
    </script>
    <!-- End Google Analytics -->

    <style type="text/css">
    /*==============================================
    FOOTER  STYLES
    =============================================*/
    footer {
        background-color:rgb(54, 158, 129);
        padding:15px 50px;
        color:#fff;
        font-size:12px;
    }

    footer a {
        color:#fff;
    }
    footer a:hover, footer a:focus {
        color:#fff;
        text-decoration:none;
    }
    .wrapper{
        top:40%;
        height: 600px;
    }
    .menu{
        background-color: #d1d5d6;
        height: 52px;
    }
    .nav{
        padding-bottom: 0px;
    }
    .navbar{
        margin-bottom: 0px;
        z-index: 1;
    }
    .navbar-default{
        background-color: #d1d5d6;
    }
    .carousel_background{
        background-color: #3d3d3d;
    }
    .navbar-header{
        background-color: transparent;
    }
    .navbar-default .navbar-toggle{
        border-color: #3d3d3d;
    }
    .lema{
        background-color: #006a62;
        color: #FFF;
        padding: 20px;
        /*margin: 10px;*/
    }

    /* Estilos gobernación */
    .panel-footer {
        font-family: 'Montserrat', sans-serif !important;
        font-size: 85%;
        color: #fff;
        background-image: url("https://framework-gb.cdn.gob.mx/landing/img/fondofooter.jpg");
        background-size: cover;
        padding: 20px 15px 10px 15px;
        background-color: #f5f5f5;
        border-top: 1px solid #ddd;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        min-height: 350px;
    }

    footer {
        background: #E3E3E3;
        background-color: rgb(227, 227, 227);
        background-image: none;
        background-size: auto;
        border-top: 1px solid #B8BEC5;
        box-shadow: 1px 1px 6px #563477;
    }

    .notifications-wrapper .nav {
        background-color: #d1d5d6;
    }

    .navbar-inverse {
        background-color: #13322b;
    }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarMainCollapse">
                        <span class="sr-only">Interruptor de Navegación</span><span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="logos" style="width: 8rem;" href="https://www.gob.mx/" target="_blank">
                        <img src="https://framework-gb.cdn.gob.mx/landing/img/logoheader.png" alt="logo gobierno de méxico">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarMainCollapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="https://www.gob.mx/tramites" title="Trámites">Trámites</a></li>
                        <li><a href="https://www.gob.mx/gobierno" title="Gobierno">Gobierno</a></li>
                        <li><a href="https://www.gob.mx/busqueda"><img src="https://framework-gb.cdn.gob.mx/landing/img/lupa.png" alt=""></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="col-md-4 menu">
        <!-- /. NAV TOP  -->
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <!-- LLAMAR NAVTOP.PHP -->
            <div class="navbar-header"></div>
            <div class="notifications-wrapper">
                <ul class="nav">
                    <li class="nav pull-right">
                        <ul class="">
                            <!-- <li>
                            <button type="button" name="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#login-modal">
                                    Inicio de sesión
                                </button>
                            </li> -->
                            <!--li>
                                <a href="#">
                                    <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/sipimss.png"
                                    height="70px"
                                    class="logos"
                                    alt="SIPIMSS"
                                    title="SIPIMSS"
                                    target="_blank"/>
                                </a>
                            </li-->
                            <li>
                                <!-- <a href="#"><img img-responsive class"logos" height="70px" src="assets/img/template_sipimss/ces.png" alt=""></a> -->
                                <a href="http://educacionensalud.imss.gob.mx" target="_blank">
                                    <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/ces.png"
                                    height="50px"
                                    class="logos"
                                    alt="CES"
                                    title="CES"
                                    target="_blank"/>
                                </a>
                            </li>
                            <li>
                                <!-- <a href="#"><img img-responsive class"logos" height="70px" src="assets/img/template_sipimss/imss.png" alt=""></a> -->
                                <a href="http://www.imss.gob.mx/" target="_blank">
                                    <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/imss.png"
                                    height="50px"
                                    class="logos"
                                    alt="IMSS"
                                    title="IMSS"
                                    target="_blank"/>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- <div class="col-md-7">

        </div>
        <div class="col-md-2">
        <button type="button" name="button" class="btn btn-success" data-toggle="modal" data-target="#login-modal">
        Login
        </button> -->
    </div>
<div class="col-md-8 menu">
    <!-- <img class="img-responsive" src="<?php echo asset_url(); ?>img/ditto/menu.png">-->
    <div class="col-md-10">
        <!-- <img src="<?php echo asset_url(); ?>img/ditto/inicio.png"> -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- <a class="navbar-brand" href="#"></a> -->
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class=""><a href="<?php echo site_url(); ?>">INICIO</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#login-modal">INICIO DE SESIÓN</a></li>
                        <!--li><a href="#" data-toggle="modal" data-target="#registro-modal">REGISTRAR</a></li-->
                        <li>
                            <a href="#" data-toggle="modal" data-target="#mesa-ayuda-modal">MESA DE AYUDA</a>
                            <!-- </ul> -->
                        </li>
                        <!--li><a href="<?php //echo base_url('assets/files/manual_actividad_docente.pdf'); ?>" download>TUTORIAL</a></li-->
                        <!-- <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li> -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="col-md-2"></div>
</div>
<div class="clearfix"></div>
<div class="carousel_background">
    <div class="col-md-14 text-center container-fluid">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <!-- <img class="img-responsive" src="<?php echo asset_url(); ?>img/ditto/carrusel.png"> -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <!-- <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
                <li data-target="#myCarousel" data-slide-to="4"></li>
                <li data-target="#myCarousel" data-slide-to="3"></li>
                <li data-target="#myCarousel" data-slide-to="5"></li>
                <li data-target="#myCarousel" data-slide-to="6"></li>
                <li data-target="#myCarousel" data-slide-to="7"></li>
                <li data-target="#myCarousel" data-slide-to="8"></li>
                <li data-target="#myCarousel" data-slide-to="9"></li>
            </ol> -->

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="<?php echo asset_url(); ?>img/ditto/IMG-20201008-WA0014.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
                    <!-- <div class="carousel-caption">
                    <h3>Eleva tu reconocimiento profesional actualizando</h3>
                    <p>tu información personal, profesional, tus actividades docentes y de investigación en el IMSS</p>
                </div> -->
            </div>
            <!-- <div class="item">
            <img src="<?php echo asset_url(); ?>img/ditto/SIPIMSS_carrusel03.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
        </div>
        <div class="item">
        <img src="<?php echo asset_url(); ?>img/ditto/1.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
    </div>
    <div class="item">
    <img src="<?php echo asset_url(); ?>img/ditto/3.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div> -->
<!-- <div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/2.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div> -->
<!-- <div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/4.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div>
<div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/5.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div>
<div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/6.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div>
<div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/7.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div>
<div class="item">
<img src="<?php echo asset_url(); ?>img/ditto/8.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS">
</div> -->
</div>

<!-- Left and right controls
<a class="left carousel-control" href="#myCarousel" data-slide="prev">
<span class="glyphicon glyphicon-chevron-left"></span>
<span class="sr-only">Anterior</span>
</a>
<a class="right carousel-control" href="#myCarousel" data-slide="next">
<span class="glyphicon glyphicon-chevron-right"></span>
<span class="sr-only">Siguiente</span>
</a> -->
</div>

</div>
<div class="col-md-2"></div>
</div>
</div>
<div class="clearfix"></div>
<div class="col-md-14 text-center">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <h4 class="lema">El censo de docentes contribuye a la misión de avanzar en la docencia y la investigación
            al posibilitar la identificación de oportunidades y la optimización de los recursos humanos.</h4>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-14 text-justify">
        <div class="col-md-2"></div>
        <div class="col-md-4">El Sistema de Información de Docentes del Instituto Mexicano del Seguro Social, que tiene como propósito, concentrar la información profesional actualizada y confiable del personal de salud con actividad docente en el IMSS, lo que permite realizar una mejor programación, toma de decisiones y evaluación.</div>
        <div class="col-md-4"><img class="img-responsive" src="<?php echo asset_url(); ?>img/ditto/IMG-20201008-WA0025.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS"></div>
        <!-- <div class="col-md-2"><h4>TUTORIALES</h4>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis ante sed tortor condimentum consectetur. </div>
        <div class="col-md-2"><img class="img-responsive" src="<?php echo asset_url(); ?>img/ditto/SIPIMSS_carrusel03.jpg" alt="Eleva tu reconocimiento profesional actualizando tu información personal, profesional, tus actividades docentes y de investigación en el IMSS"></div> -->
        <div class="col-md-2"></div>
    </div>
    <div class="clearfix"></div><br>

    <?php if (isset($my_modal))
    { ?>
        <?php echo $my_modal; ?>
    <?php } ?>
<div id="registro_modal_content">
    <?php if (isset($registro_modal))
    { ?>
        <?php echo $registro_modal; ?>
    <?php } ?>
</div>
    <div class="modal fade" id="mesa-ayuda-modal" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4><span class="glyphicon glyphicon-lock"></span>Mesa de ayuda</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                    <div class="login-page">
                        <p>¿Tienes alguna duda? Comunícate con nosotros:</p>
                        <p><!--strong>Teléfono:</strong> ???<br><strong-->Correo electrónico:</strong> <a href="mailto:genaro.sanchez@imss.gob.mx">genaro.sanchez@imss.gob.mx</a><br><strong>Horario:</strong>&nbsp;de lunes a&nbsp;viernes, de&nbsp;8h&nbsp;a 16h</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--div class="navbar navbar-fixed-bottom">
        <footer class="text-center">
            &copy; <a href="#" target="_blank">IMSS 2020</a>
            <br>
        </footer>
    </div-->
    
    <!-- Inicio de Pie de página -->
<footer class="panel-footer hidden-xs" id="pie">
    <!--section id="f-header" class="container-fluid">
        <div class="container">
            <div class="region region-separator3">
                <section id="block-block-2" class="block block-block contextual-links-region clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-12" id="sn-title"><h4 style="text-align: center;">&nbsp;</h4></div>
                </section>
            </div>
        </div>
    </section-->
    <div class="col-md-1 col-lg-1"></div>
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
        <section id="f-CES" class="col-xs-12 col-sm-6 col-md-3">
            <div class="region region-footer-t1">
                <section id="block-block-41" class="block block-block contextual-links-region clearfix">
                    <p><img alt="" src="http://educacionensalud.imss.gob.mx/?q=es/system/files/logofooter.png" style="width: 229px; height: 76px;"></p>
                </section>
            </div>
        </section>
        <section id="f-menu" class="col-xs-12 col-sm-6 col-md-3">
            <div class="region region-footer-t2">
                <section id="block-block-33" class="block block-block contextual-links-region clearfix">
                    <p><span style="font-size:16px;"><span style="color:#ffffff; font-weight: bold;">Enlaces</span></span></p>
                    <p><span style="font-size:14px;">
                        <a href="https://www.gob.mx/participa"><span style="color:#ffffff;">Participa</span></a><br>
                        <a href="https://www.gob.mx/publicaciones"><span style="color:#ffffff;">Publicaciones Oficiales</span></a><br>
                        <a href="http://www.ordenjuridico.gob.mx/"><span style="color:#ffffff;">Marco Jurídico</span></a><br>
                        <a href="https://consultapublicamx.inai.org.mx/vut-web/"><span style="color:#ffffff;">Plataforma Nacional de Transparencia</span></a>
                    </span></p>
                </section>
            </div>
        </section>
        <section id="f-contact" class="col-xs-12 col-sm-12 col-md-3">
            <div class="fborder">
                <div class="region region-footer-t3">
                    <section id="block-block-1" class="block block-block contextual-links-region clearfix">
                        <div class="center-block block-contact withadress">
                            <address class="faddress">
                                <p><span style="color:#ffffff;"><span style="font-size:16px; font-weight: bold;">¿Qué es gob.mx?</span></span></p>
                                <p><span style="font-size:14px;"><span style="color:#ffffff;">Es el portal único de trámites, información y participación ciudadana.&nbsp;</span><ins><a href="https://www.gob.mx/que-es-gobmx"><span style="color:#ffffff;">Leer más</span></a></ins></span></p>
                                <p><span style="font-size:14px;"><a href="https://datos.gob.mx/"><span style="color:#ffffff;">Portal de datos abiertos</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/accesibilidad"><span style="color:#ffffff;">Declaración de accesibilidad</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/privacidadintegral"><span style="color:#ffffff;">Aviso de privacidad integral</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/privacidadsimplificado"><span style="color:#ffffff;">Aviso de privacidad simplificado</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/terminos"><span style="color:#ffffff;">Términos y condiciones</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/terminos#medidas-seguridad-informacion"><span style="color:#ffffff;">Política de seguridad</span></a></span></p>
                                <p><span style="font-size:14px;"><a href="https://www.gob.mx/sitemap"><span style="color:#ffffff;">Mapa del sitio</span></a></span></p>
                            </address>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        <section id="f-contact" class="col-xs-12 col-sm-12 col-md-3">
            <div class="fborder">
                <div class="region region-footer-t4">
                    <section id="block-block-42" class="block block-block contextual-links-region clearfix">
                        <p><span style="color:#ffffff;"><span style="font-size:16px; font-weight: bold;">Otros trámites</span></span></p>
                        <p><span style="color:#ffffff;"><span style="font-size:14px;">Mesa de ayuda: dudas e información</span></span></p>
                        <p><span style="color:#ffffff;"><span style="font-size:14px;"><a href="mailto:gobmx@funcionpublica.gob.mx">gobmx@funcionpublica.gob.mx</a></span></span></p>
                        <p><span style="color:#ffffff;"><span style="font-size:14px;">Denuncia contra servidores públicos</span></span></p>
                        <p><span style="color:#ffffff;"><span style="font-size:14px;">Síguenos en:</span></span></p>
                        <p><span style="font-size:14px;"><br><a href="https://www.facebook.com/gobmexico/"><img alt="" src="http://educacionensalud.imss.gob.mx/?q=es/system/files/facebook.png" style="width: 24px; height: 24px;"></a><a href="https://twitter.com/GobiernoMX"><img alt="" src="http://educacionensalud.imss.gob.mx/?q=es/system/files/twitter.png" style="width: 24px; height: 24px;"></a></span></p>
                    </section>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-1 col-lg-1"></div>
</footer>
    <script>
    <?php
    if (isset($errores))
    {
        ?>
        $('#login-modal').modal({show: true});
        <?php
    }

    if (isset($user_recovery) || isset($code_recovery))
    {
        ?>
        $('#modalRecovery').modal({show: true});
        <?php
    }
    ?>
    </script>
    </body>
    </html>
