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
                        <?php $docente_id = (!is_null($id_docente))?'/'.$id_docente:''; ?>
                        <a href="<?php echo site_url('perfil/perfil_impresion'.$docente_id);?>" target="_blank"> <i class="fa fa-print"></i></a>
                    </h1>
                <?php } ?>
            </div>
            <?php if(isset($elementos_seccion['ruta_imagen_perfil']) && !is_null($elementos_seccion['ruta_imagen_perfil'])){ ?>
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
            <?php } ?>   
            <div class="panel panel-default">
            
                <div class="panel-body">                    
                    <h2><?php echo $string_value['title_seccion']; ?></h2>
                    <div  class="col-md-6">
                        <?php
                            if (isset($informacion_imss)) {
                                echo $informacion_imss;
                            }
                            ?>
                        
                    </div>
                    <div  class=" col-md-6">
                        <?php
                            if (isset($informacion_general)) {
                                echo $informacion_general;
                            }
                        ?>
                    </div>
                </div>
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
