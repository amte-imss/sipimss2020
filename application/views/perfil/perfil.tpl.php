<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //echo css('template_sipimss/style_profile.css'); ?>
<div id="page-wrapper" class="page-wrapper">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!is_null($titulo_seccion)) {//Pinta sección  
                    ?>
                    <h1 class="page-head-line">
                        <?php echo $titulo_seccion; ?>
                        <a href="<?php echo site_url('perfil/perfil_impresion');?>" target="_blank"> <i class="fa fa-print"></i></a>
                    </h1>
                <?php } ?>
            </div>
            <div class="col-md-5 col-5" id="main_content">
                <!-- <div class="container">
                    <div class="card-profile"> -->
                        <div class=""><!-- card-profile_visual -->
                            <!-- <img img-responsive class="logos" height="70px" src="assets/img/template_sipimss/3.png" alt=""> -->
                            <img src="<?php echo $elementos_seccion['ruta_imagen_perfil']; ?>" class="img-responsive" style="padding-bottom: 10px; padding-right: 10px;">
                        </div>
                        <!--<div class="card-profile_user-infos">
                            <span class="infos_name"></span>
                            <a href="#"></a>
                        </div>
                    </div>
                </div> -->
            </div>
            <br>
            <div class="perfil_cards col-md-3 col-sm-3">
                <div id="" class="style-box-one Style-one-clr-two" >
                    <a href="#" class="showSingle" data-target="1">
                        <!-- <img img-responsive class="logos" height="70px" src="assets/img/template_sipimss/3.png" alt=""> -->
                        <h5>Información General LIMA</h5>
                    </a>
                </div>

            </div>
            <div class="perfil_cards col-md-3 col-sm-3">
                <div class="style-box-one Style-one-clr-three">
                    <a href="#" class="showSingle" data-target="2">
                        <img img-responsive class="logos" height="70px" src="assets/img/template_sipimss/6.png" alt="">
                        <h5>Información IMSS</h5>
                    </a>
                </div>
            </div>
            <br>

            <div  class="div-frame box-info content-container col-md-6">
                <?php
                if ($informacion_general) {
                    echo $informacion_general;
                }
                ?>
                <?php
                if ($informacion_imss) {
                    echo $informacion_imss;
                }
                ?>

            </div>

            <div class="row">
                <!--<section class="task-panel tasks-widget">
                    <div class="row"> -->
                        <div class="col-md-12 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php
                                    if ($main_content) {
                                        echo $main_content;
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    <!-- </div>
                </section> -->
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function () {
        //  jQuery('#showall').click(function(){
        //        jQuery('.targetDiv').show();
        // });
        jQuery('.showSingle').click(function () {
            jQuery('.targetDiv').hide();
            jQuery('#div' + $(this).attr('data-target')).show();
        });
    });

</script>


<!-- <script>
//custom select box

    $(function () {
        $('select.styled').customSelect();
    });

</script> -->
