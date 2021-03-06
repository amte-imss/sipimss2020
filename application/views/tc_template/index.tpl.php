<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/img/template_sipimss/apple-icon.png'); ?>" />
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/template_sipimss/favicon.ico'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?php echo (!is_null($title)) ? "{$title}&nbsp;|" : "" ?>
        <?php echo (!is_null($main_title)) ? $main_title : "Sistema de Información de Docentes del IMSS" ?>
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- BOOTSTRAP STYLES-->
    <?php echo css('bootstrap.css'); ?>
    <!-- FONTAWESOME ICONS STYLES-->
    <!--CUSTOM STYLES-->
    <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
    var url = "<?php echo base_url(); ?>";
    var site_url = "<?php echo site_url(); ?>";
    var img_url_loader = "<?php echo base_url('assets/img/loader.gif'); ?>";
    //var aviso_privacidad = "<?php //echo $aviso_privacidad; ?>";
    </script>
    <?php echo css('estilo_perfil.css'); ?>
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <?php echo css('font-awesome.css'); ?>
    <?php echo css('style.css'); ?>
    <?php echo css('template_sipimss/sipimss.css'); ?>
    <?php echo css("date/datepicker.css"); ?>
    <?php echo css("datepicker.less"); ?>

    <?php echo css('template_sipimss/apprise.css'); ?>


    <?php echo js("jquery.js"); ?>
    <?php echo js("jquery.min.js"); ?>
    <?php echo js("jquery.ui.min.js"); ?>
    
    <!--Let browser know website is optimized for mobile-->
</head>
<body>
    <div id="overlay">
        <img src="<?php echo base_url('assets/img/loader.gif'); ?>" alt="Loading" /><br/>
        Cargando...
    </div>

    <div class="modal fade" id="my_modal" tabindex="3" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" id="my_modal_content" role="document">
            <?php
            if (isset($my_modal))
            {
                ?>
                <?php echo $my_modal; ?>
            <?php } ?>
        </div>
    </div>
    <div id="wrapper">
        <!-- /. NAV TOP  -->
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">

            <!-- LLAMAR NAVTOP.PHP -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a  class="navbar-brand" href="<?php echo site_url(); ?>">IMSS :: CES
                </a>
            </div>
            <div class="notifications-wrapper">
                <ul class="nav">

                    <?php
                    if(isset($this->session->get_userdata()['die_sipimss']['usuario']))
                    {
                        $datos_sesion = $this->session->get_userdata()['die_sipimss']['usuario'];
                        if($datos_sesion['is_alias_sesion']){
                            $datos_sesion['matricula'] = 'NA';
                            $datos_sesion['categoria'] = 'NA';
                            $datos_sesion['delegacion'] = 'NA';
                            $datos_sesion['unidad'] = 'NA';
                        }
                        //pr($datos_sesion);
                    }else
                    {
                        $datos_sesion = array(
                            'nombre' => '', 'apellido_p' => '', 'apellido_m' => '',
                            'matricula' => '', 'categoria' => '', 'delegacion' => '',
                            'unidad' => ''
                        );
                    }

                    ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user-plus"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">

                            <?php // pr($datos_sesion); ?>
                            <li><a class="link_ficha_usuario" href="#">
                                <b>Nombre:</b> <?php echo $datos_sesion['nombre'] . ' ' . $datos_sesion['apellido_p'] . ' ' . $datos_sesion['apellido_m']; ?> <br>
                                <b>Matrícula:</b> <?php echo $datos_sesion['matricula']; ?><br>
                                <b>Categoría:</b> <?php echo $datos_sesion['categoria']; ?><br>
                                <b>OOAD:</b> <?php echo $datos_sesion['delegacion']; ?> <br>
                                <b>Unidad:</b> <?php echo $datos_sesion['unidad']; ?><br>
                                <!--<b>Rol:</b> <?php // echo $datos_sesion['nombre_role'];         ?><br>-->
                                <div class="ripple-container"></div></a>
                            </li>
                            
                            <!--li><a href="<?php //echo site_url(); ?>/perfil"><i class="fa fa-user-plus"></i> Mi perfil</a></li-->
                            <li><a href="<?php echo site_url(); ?>/inicio/cerrar_sesion"><i class="fa fa-sign-out"></i> Cerrar sesión</a></li>
                        </ul>
                    </li>
                                       

                    <?php if(isset($notificaciones_estaticas))
                    {
                        echo $notificaciones_estaticas;
                    }
                    ?>
                    <!-- <li >
                    <a id="item-contacto" href="#item-contacto" class="tablero-menu-item"
                    data-toggle="modal" data-target="#admin-finalizacenso">
                    <i class="dashboard"></i>De clic aquí para finalizar el registro de convocatoria</a>
                </li> -->
                <?php
                if(isset($fin_registro_datos_censo_docente) && $fin_registro_datos_censo_docente){                    
                        ?>
                        <li>
                            <a id="item-contacto" href="#item-contacto" class="tablero-menu-item"
                            data-toggle="modal" data-target="#admin-finalizacenso" style="text-decoration: none; background-color: #006a62 !important; color: #fff;">
                            <i class="dashboard"></i>De clic aquí para finalizar el registro de datos y envíar a validación</a>
                        </li>
                        <?php
                    
                }
                ?>
                <?php echo $finaliza_etapas;?>

                <?php if(isset($this->session->userdata('die_sipimss')['anterior']))
                {
                    ?>
                    <li class="nav">
                        <a href="<?php echo site_url('administracion/terminar_entrar_como'); ?>"> Regresar a la sesión original</a>
                    </li>
                <?php } ?>
                <?php if(ENVIRONMENT=='development' && isset($datos_sesion['workflow']) && count($datos_sesion['workflow']) > 0){
                    ?>
                    <li>
                        Solo en desarrollo <br>Etapa activa del usuario: <?php echo $datos_sesion['workflow'][0]['etapa_activa']; ?>
                    </li>
                    <?php
                }?>

                <li class="nav pull-right">
                    <ul class="">
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
                        <li class="" style="list-style-type: none;">
                            <a href="<?php echo site_url('inicio/cerrar_sesion'); ?>" id="tablero-menu-item-Mrs6sGg9ud" class="tablero-menu-item " style="color: #fff; background: #07cec0; text-shadow: none; border-bottom: 1px solid #FFF; padding: 30px; margin-right: 25px; font-weight: bold; font-size: 14px;">
                                <i class="dashboard"></i>Cerrar sesión</a>
                        </li>
                        <li>
                            <!-- <a href="#"><img img-responsive class"logos" height="70px" src="assets/img/template_sipimss/ces.png" alt=""></a> -->
                            <a href="http://educacionensalud.imss.gob.mx">
                                <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/ces.png"
                                height="70px"
                                class="logos"
                                alt="CES"
                                title="CES"
                                target="_blank"/>
                            </a>
                        </li>
                        <li>
                            <!-- <a href="#"><img img-responsive class"logos" height="70px" src="assets/img/template_sipimss/imss.png" alt=""></a> -->
                            <a href="http://www.imss.gob.mx/">
                                <img img-responsive src="<?php echo asset_url(); ?>img/template_sipimss/imss.png"
                                height="70px"
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

    <nav  class="navbar-default navbar-side " role="navigation">
        <div class="sidebar-collapse">
            <!-- AQUI VA EL MENU LATERAL -->
            <?php
            if (isset($menu) && !is_null($menu))
            {
                // pr ($menu);
                //echo $menu;
                echo render_menu($menu['lateral'], null, $menu['secciones'], current_url(true));
            }
            ?>

        </div>


    </nav>
    <!-- /. SIDEBAR MENU (navbar-side) -->
    <div id="main-content" class="page-wrapper-cls">
        <?php
        if (isset($blank))
        {
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <?php
                    echo $blank;
                    ?>
                </div>
            </div>
        <?php } //fin blank zone  ?>

        <?php ?>
        <div class="row" style="margin-right: 0px;">
            <div class="col-md-12">
                <div class="">
                    <?php
                    if (isset($sub_title) && !empty($sub_title))
                    {
                        ?>
                        <div class="card-header" data-background-color="purple">
                            <h4 class="title">
                                <?php echo $sub_title; ?>
                            </h4>
                            <?php
                            if (isset($descripcion) && !empty($descripcion))
                            {
                                ?>
                                <p class="category">
                                    <?php echo $descripcion ?>
                                </p>
                            <?php } ?>
                        </div>
                        <?php
                    }
                    if (isset($main_content))
                    {
                        ?>
                        <div class="card-content">
                            <?php
                            echo $main_content;
                            ?>
                        </div>
                    <?php } //fin content card      ?>
                </div>
            </div>
        </div>


        <!-- /. PAGE WRAPPER  -->
    </div>
</div>
<!-- /. WRAPPER  -->
<!-- /. FOOTER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<!-- <script src="assets/js/jquery-2.js"></script> -->

<footer style="min-height:170px;">
    <div class="col-md-1"></div>

    <div class="col-md-5">
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b><a href="http://educacionensalud.imss.gob.mx/?q=es/aviso-de-privacidad" target="_blank">Aviso de privacidad</a></b>        
        <br>
        <br>
        &copy; <a href="#" target="_blank">IMSS 2020</a>
    </div>

    <div class="col-md-2"></div>

    <div class="col-md-4">
        <b>Mesa de ayuda</b>
        <br>
        ¿Tienes alguna duda? Comunícate con nosotros:
        <!--br>
        <b>Teléfono:</b> ???-->
        <br>
        <b>Correo electrónico:</b> genaro.sanchez@imss.gob.mx
        <br>
        <b>Horario:</b> de lunes a viernes, de 8h a 16h
    </div>
    <div class="col-md-4">

    </div>
</footer>

<!-- BOOTSTRAP SCRIPTS -->
<?php echo js("bootstrap.js"); ?>
<?php echo isset($date_picker) && $date_picker ? js("date/bootstrap-datepicker.js"): ''; ?>
<!-- METISMENU SCRIPTS -->
<?php echo js("jquery.metisMenu.js"); ?>
<!-- <?php //echo js("jquery-2.js"); ?> -->


<!-- CUSTOM SCRIPTS -->
<?php echo js("/custom.js"); ?>
<?php echo js('template_sipimss/general.js'); ?>
<?php echo js('template_sipimss/apprise.js'); ?>
<?php echo js('menu.js'); ?>
<?php echo js('/convocatoria/convocatoria.js'); ?>
<?php echo js('ayuda.js'); ?>

<!--Import jQuery before materialize.js-->
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="assets/js/materialize.min.js"></script> -->

<script type="text/javascript">
// Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
    interval: false
});
// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item
</script>
<script type="text/javascript">
$('#info_siap_modal').modal('show');
</script>

<div class="modal fade" id="admin-finalizacenso" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span>Finalizar registro</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
                <p>Al dar clic en "Finalizar registro" se dará por concluido el registro docente, no le permitirá editar más sus datos.</p> 
                <p>Por favor confirme la terminación.</p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <a href="<?php echo site_url('convocatoriaV2/guardar_registro_finaliza_convocatoria_docente_censo/'); ?>" class="btn btn-primary">
                    Finalizar registro
                </a>
                <!-- <button type="submit" class="btn btn-primary" onclick="finalizar_censo(this)">Finalizar censo</button>-->
            </div>
        </div>
    </div>
</div>
<?php
//pr($this->session->userdata('die_sipimss')['usuario']); exit();
if(!$this->session->userdata('die_sipimss')['usuario']['aviso_privacidad']){
    //pr($this->session->userdata('die_sipimss'));
    ?>
    <div class="modal fade" id="aviso_privacidad" tabindex="-1" role="dialog" style="display: none;" data-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center"  style="padding:35px 50px;" > 
                    <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                    <h4 ><span class="glyphicon glyphicon-lock"></span >Aviso</h4>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                    <h4><p>Consulta el Aviso de privacidad de la Coordinaci&oacute;n de</p></h4>
                    <h4><p>Educaci&oacute;n en Salud en:</p></h4>                 
                    <br>
                    <h4><p><a href="http://educacionensalud.imss.gob.mx/?q=es/aviso-de-privacidad" target="_blank">http://educacionensalud.imss.gob.mx/?q=es/aviso-de-privacidad</a></p></h4>
                    <br>
                    <form id="form_aviso" name="form_aviso">
                    <label><input type="checkbox" value="1" id="confirm_privacidad" name="confirm_privacidad"> Confirmo que he leído el aviso de privacidad.</label><br>
                    <div class="text-center"><button type="submit" id="btn_confirm_privacidad" class="btn btn-primary" onclick="confirmar_aviso_privacidad();">Confirmar</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="resultado_aviso_privacidad"></div>
    <script>
    $(document).ready(function () {
        //if (typeof aviso_privacidad !== 'undefined' && (aviso_privacidad == 1 || aviso_privacidad == "1")){
            $("#aviso_privacidad").modal();
            $("#btn_confirm_privacidad").attr('disabled', 'disabled').css('cursor', 'no-drop');
            $("#confirm_privacidad").change(function() {
                if(this.checked) {
                    $("#btn_confirm_privacidad").removeAttr('disabled').css('cursor', 'pointer');
                } else {
                    $("#btn_confirm_privacidad").attr('disabled', 'disabled').css('cursor', 'no-drop');
                }
            });
        //}
    });
    function confirmar_aviso_privacidad(){
        $('#form_aviso').submit(function(event){
            event.preventDefault();
            data_ajax(site_url + '/inicio/confirmar_aviso_privacidad', '#form_aviso', '#resultado_aviso_privacidad', function(){
                location.reload();
            });
        });
    }
    </script>
<?php } ?>
</body>
</html>
